<?php

namespace App\Core\Repository\PDO;


use App\Core\Repository\QueryServiceInterface;
use App\Core\Repository\StoredProcedureInterface;


class QueryServiceServicePDO implements QueryServiceInterface
{
    /**
     * @var \PDO
     */
    protected $_link;

    public function __construct(\PDO $link)
    {
        $this->_link = $link;
    }

    public function executeQuery(string $query, callable $rowMapper = null) : array
    {
        if (!$stmt = $this->_link->query("$query"))
            return null;

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return is_null($rowMapper) ? $result : array_map($rowMapper, $result);
    }

    public function executeNonQuery(string $query)
    {
        return $this->_link->exec("$query");
    }
}