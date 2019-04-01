<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 19.02.2019
 * Time: 00:45
 */

namespace App\Core\Repository;


interface QueryServiceInterface
{
    function executeQuery(string $query, callable $rowMapper = null) : array;

    function executeNonQuery(string $query);
}