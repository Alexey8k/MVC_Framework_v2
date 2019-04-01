<?php

namespace App\Core\Repository\MySQLi;


use App\Core\Repository\DbFactoryInterface;
use App\Core\Repository\QueryServiceInterface;
use App\Core\Repository\StoredProcedureInterface;

class DbFactoryMySQLi implements DbFactoryInterface
{

    private $_link;

    private $_queryService;

    function __construct(array $connectionOptions)
    {
        $this->_link = new \MySQLi(
            $connectionOptions['host'],
            $connectionOptions['userName'],
            $connectionOptions['password'],
            $connectionOptions['dbName']
        );
    }

    function dbLink()
    {
        return $this->_link;
    }

    function queryService(): QueryServiceInterface
    {
        return $this->_queryService ?? $this->_queryService = new QueryServiceServiceMySQLi($this->_link);
    }

    function storedProcedure(string $name, bool $isFunction): StoredProcedureInterface
    {
        return new StoredProcedureMySQLi($name, $this->_link, $isFunction);
    }
}