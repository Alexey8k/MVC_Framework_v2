<?php

namespace App\Core\Route;

class RouteCollection implements \IteratorAggregate
{
    private $_routes = [];

    public function mapRoute(string $url, array $defaults = [], array $constraints = [])
    {
        $route = new Route($url, $defaults, $constraints);
        $this->_routes[] = $route;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->_routes);
    }
}