<?php

namespace App\Core\Interceptor;

class InterceptorManager
{
    /**
     * @var string
     */
    private $controllerName;
    /**
     * @var HandlerInterceptor[]
     */
    private static $handlers;

    public function __construct(string $controllerName)
    {
        $this->controllerName = $controllerName;
    }

    public function executeHandlers() : bool {
//        $handlers = array_filter(InterceptorManager::$handlers, function (HandlerInterceptor $el) {
//            return $el->getControllerName() === $this->controllerName;
//        });
//        foreach ($handlers as $handler)
//            if (!$handler->handler()) return false;

        return true;
    }

    public static function addHandler(HandlerInterceptor $handlerInterceptor) {
        InterceptorManager::$handlers[] = $handlerInterceptor;
    }
}