<?php

namespace App\Core\Repository;


use App\Core\Repository\MySQLi\DbFactoryMySQLi;
use App\Core\Repository\PDO\DbFactoryPDO;

abstract class Db
{
    public static function createDbFactory(array $connectionOptions) : DbFactoryInterface {
        switch ($connectionOptions['dbExtension']) {
            case 'PDO': return new DbFactoryPDO($connectionOptions);
            case 'MySQLi': return new DbFactoryMySQLi($connectionOptions);
            default: return null;
        }
    }
}