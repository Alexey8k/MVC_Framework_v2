<?php

namespace App\Core\Interceptor;

abstract class HandlerInterceptor
{
    /**
     * @var string
     */
    private $controllerName;

    protected function __construct(string $controllerName)
    {
        $this->controllerName = $controllerName;
    }

    public abstract function handler() : bool;

    public function getControllerName() : string {
        return $this->controllerName;
    }
}