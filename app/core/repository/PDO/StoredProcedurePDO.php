<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 18.02.2019
 * Time: 22:13
 */

namespace App\Core\Repository\PDO;


use App\Core\Repository\Parameter;
use App\Core\Repository\StoredProcedure;
use App\Core\Repository\StoredProcedureInterface;


class StoredProcedurePDO extends StoredProcedure implements StoredProcedureInterface
{
    /**
     * @var \PDO
     */
    private $link;

    private const ParamTypes = ['i' => \PDO::PARAM_INT, 's' =>\PDO::PARAM_STR, 'd' => \PDO::PARAM_STR, 'b' => \PDO::PARAM_LOB];

    public function __construct(string $name, \PDO $link, bool $isFunction = false)
    {
        parent::__construct($name, $isFunction);
        $this->link = $link;
    }

    public function returningResultSet(string $paramName, callable $rowMapper = null) : StoredProcedureInterface {
        parent::returningResultSet($paramName, $rowMapper);
        return $this;
    }

    public function addParam(string $type, $value) : StoredProcedureInterface {
        parent::addParam($type, $value);
        return $this;
    }

    public function execute() {
        $stmt = $this->link->prepare($this->strStoredProcedure());
        if(count($this->params))
            array_walk($this->params, function (Parameter $value, $key) use($stmt) {
                $stmt->bindParam($key + 1, $value->value, self::ParamTypes[$value->type]);
            });
        return $this->isFunction ? $this->executeFunction($stmt) : $this->executeProcedure($stmt);
    }

    private function executeFunction(\PDOStatement $stmt) {
        $stmt->execute();
        return $stmt->fetch()[0];
    }

    private function executeProcedure(\PDOStatement $stmt) : array {
        $result = [];
        $stmt->execute();
        do {
            $key = count($this->rowMappers) != 0 ? array_keys($this->rowMappers)[0] : $this->getDefaultKey(array_keys($result));
            $rowMapper = array_shift($this->rowMappers);
            $result[$key] = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $result[$key][] = !is_null($rowMapper) ? $rowMapper($row) : $row;
            }
        } while($stmt->nextRowset());
        return $result;
    }
}