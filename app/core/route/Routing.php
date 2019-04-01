<?php

namespace App\Core\Route;

use App\Core\Action\Action;
use App\Core\Interceptor\InterceptorManager;
use App\App_Start\RouteConfig;

abstract class Routing
{
    /**
     * @var RouteCollection
     */
    private static $routes;

    private static $routeData;

    private static $requestUrl;

    public static function start()
    {
        Routing::$requestUrl = $_SERVER['REQUEST_URI'];
        Routing::$routes = new RouteCollection();
        RouteConfig::registerRoutes(Routing::$routes);
        Routing::$routeData = Routing::getRouteData();
        if (!Routing::$routeData)
            throw new \Exception('Url-адрес не соответствует не одному маршруту.');

        $controller = static::getControllerName();
        $action = static::getActionName();

        if ((new InterceptorManager($controller))->executeHandlers())
            Routing::actionExecute($action, $controller);
    }

    public static function getRequestUrl() : string
    {
        return Routing::$requestUrl;
    }

    public static function getRouteData()
    {
        if (Routing::$routeData != null)
            return Routing::$routeData;

        foreach (Routing::$routes as $route)
        {
            if (Routing::$routeData = $route->getRouteData(Routing::$requestUrl)) break;
        }

        return Routing::$routeData;
    }

    public static function getUrl(array $routeValues)
    {
        $url = null;

        foreach (Routing::$routes as $route)
        {
            if ($url = $route->getUrl($routeValues)) return $url;
        }

        return null;
    }

    public static function actionExecute(string $action, string $controller)
    {
        Routing::$routeData['action'] = $action;
        $action = new Action($action, $controller);
        $action->execute();
    }

    private static function getControllerName()
    {
        $controllerName = Routing::$routeData['controller'] ?? null;
        if (!isset($controllerName) && empty($controllerName))
            throw new \Exception('Соответствующий маршрут не включает значение маршрута "controller", которое требуется.');
        return $controllerName;
    }

    private static function getActionName()
    {
        $actionName = Routing::$routeData['action'] ?? null;
        if (!isset($actionName) && empty($actionName))
            throw new \Exception('Соответствующий маршрут не включает значение маршрута "action", которое требуется.');
        return $actionName;
    }
}