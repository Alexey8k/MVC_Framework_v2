<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19.02.2019
 * Time: 00:49
 */

namespace App\Core\Repository;


interface StoredProcedureInterface
{
    public function returningResultSet(string $paramName, callable $rowMapper = null) : StoredProcedureInterface;

    public function addParam(string $type, $value) : StoredProcedureInterface;

    public function execute();
}