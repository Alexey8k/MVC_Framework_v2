<?php

namespace App\Core\Repository\PDO;


use App\Core\Repository\DbFactoryInterface;
use App\Core\Repository\QueryServiceInterface;
use App\Core\Repository\StoredProcedureInterface;

class DbFactoryPDO implements DbFactoryInterface
{
    private $_link;

    private $_queryService;

    function __construct(array $connectionOptions)
    {
        $this->_link = new \PDO(
            'mysql:host='.$connectionOptions['host'].';dbname='.$connectionOptions['dbName'],
            $connectionOptions['userName'],
            $connectionOptions['password']
        );
    }

    function dbLink()
    {
        return $this->_link;
    }

    function queryService(): QueryServiceInterface
    {
        return $this->_queryService ?? $this->_queryService = new QueryServiceServicePDO($this->_link);
    }

    function storedProcedure(string $name, bool $isFunction): StoredProcedureInterface
    {
        return new StoredProcedurePDO($name, $this->_link, $isFunction);
    }
}