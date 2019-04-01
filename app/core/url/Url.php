<?php

namespace App\Core\Url;

use App\Core\Route\Routing;

abstract class Url
{
    public static function action(string $action, string $controller, array $queryParams = null)
    {
        $routeValues = array_merge(['action'=>$action,'controller'=>$controller], $queryParams ?? []);

        return Routing::getUrl($routeValues);
    }
}