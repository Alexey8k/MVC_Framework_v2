<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19.02.2019
 * Time: 00:39
 */

namespace App\Core\Repository;


interface DbFactoryInterface
{
    function dbLink();
    function queryService() : QueryServiceInterface;
    function storedProcedure(string $name, bool $isFunction) : StoredProcedureInterface;
}