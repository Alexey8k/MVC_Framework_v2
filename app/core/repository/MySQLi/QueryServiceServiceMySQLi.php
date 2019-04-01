<?php

namespace App\Core\Repository\MySQLi;


use App\Core\Repository\QueryServiceInterface;

class QueryServiceServiceMySQLi implements QueryServiceInterface
{
    /**
    * @var \MySQLi
    */
    protected $_link;

    public function __construct(\MySQLi $link)
    {
        $this->_link = $link;
    }

    function executeQuery(string $query, callable $rowMapper = null): array
    {
        if (!$queryResult = $this->_link->query("$query"))
            return null;

        $result = $queryResult->fetch_all(MYSQLI_ASSOC);
        $queryResult->free();

        if (is_null($rowMapper))
            return $result;

        return array_map($rowMapper, $result);
    }

    function executeNonQuery(string $query)
    {
        if (!$queryResult = $this->_link->query("$query"))
            return null;

        return $queryResult;
    }
}