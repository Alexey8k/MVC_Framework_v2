<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19.02.2019
 * Time: 02:18
 */

namespace App\Core\Repository\MySQLi;


use App\Core\Repository\Parameter;
use App\Core\Repository\StoredProcedure;
use App\Core\Repository\StoredProcedureInterface;

class StoredProcedureMySQLi extends StoredProcedure implements StoredProcedureInterface
{
    /**
     * @var \MySQLi
     */
    private $link;

    public function __construct(string $name, \MySQLi $link, bool $isFunction = false)
    {
        parent::__construct($name, $isFunction);
        $this->link = $link;
    }

    public function returningResultSet(string $paramName, callable $rowMapper = null): StoredProcedureInterface
    {
        parent::returningResultSet($paramName, $rowMapper);
        return $this;
    }

    public function addParam(string $type, $value): StoredProcedureInterface
    {
        parent::addParam($type, $value);
        return $this;
    }

    public function execute() {
        $stmt = $this->link->prepare($this->strStoredProcedure());
        if(count($this->params))
            call_user_func_array(array($stmt, 'bind_param'), array_merge(array($this->getTypesString()), $this->getParamValuesByRef()));
        return $this->isFunction ? $this->executeFunction($stmt) : $this->executeProcedure($stmt);
    }

    private function executeFunction(\mysqli_stmt $stmt) {
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        return $result;
    }

    private function executeProcedure(\mysqli_stmt $stmt) : array {
        $result = [];

        $stmt->execute();
        do {
            $mysqliResult = $stmt->get_result();
            if (!$mysqliResult) continue;
            $key = count($this->rowMappers) != 0 ? array_keys($this->rowMappers)[0] : $this->getDefaultKey(array_keys($result));
            $rowMapper = array_shift($this->rowMappers);
            $result[$key] = [];
            while ($row = $mysqliResult->fetch_assoc()) {
                $result[$key][] = !is_null($rowMapper) ? $rowMapper($row) : $row;
            }
            $mysqliResult->free();
        } while($stmt->more_results() && $stmt->next_result());
        $stmt->close();
        return $result;
    }

    /**
     * @return string
     */
    private function getTypesString() : string {
        return implode(array_map(function (Parameter $param) {
            return $param->type;
        }, $this->params));
    }

    private function getParamValuesByRef(){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = [];
            foreach($this->params as $key => $value)
                $refs[$key] = &$this->params[$key]->getValueByRef();
            return $refs;
        }
        return array_map(function (Parameter $param) {
            return $param->value;
        }, $this->params);
    }
}