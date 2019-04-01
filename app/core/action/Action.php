<?php

namespace App\Core\Action;

use App\Core\File;
use App\Core\Route\Routing;
use App\Core\ModelBinding\ModelBinders;

class Action
{
    private $_actionName;
    private $_controllerName;

    private static $defaultAction = 'Index';

//    function __autoload($className)
//    {
//        require_once "$className.php";
//    }

    public function __construct(string $action, string $controller)
    {
        $this->includeModel();

        $this->_controllerName = 'App\Controllers\\'. $controller . 'Controller';
        //$this->includeController();

        $this->_actionName = $this->getActionName($action);
    }

    public function execute()
    {
        $reflectionAction = new \ReflectionMethod($this->_controllerName, $this->_actionName);

        $reflectionAction->invokeArgs($this->controllerInstance(), $this->getActionParams($reflectionAction));
    }

    private function getActionName(string $action)
    {
        $actionName = 'action' . $action;
        if (method_exists($this->_controllerName, $actionName)) return $actionName;
        //throw new \Exception(", ",implode(get_class_methods(new $ns)));
        $actionName .= $_SERVER['REQUEST_METHOD'] === 'POST' ? 'Post' : 'Get';
        if (method_exists($this->_controllerName, $actionName)) return $actionName;

        throw new \Exception("Контроллер \"$this->_controllerName\" не содержит метод действия \"$action\".");
    }

    private function includeModel() : void
    {
        $routeData = Routing::getRouteData();
        $fileModel = 'app/models/'
            . ($routeData['action'] == Action::$defaultAction ? $routeData['controller'] : $routeData['action'])
            . 'Model.php';
        if (file_exists($fileModel))
            require_once $fileModel;
    }

    private function includeController()
    {
        $controllerPath = "app/controllers/$this->_controllerName.php";
        if (!file_exists($controllerPath))
            throw new \Exception("Фаил \"$this->_controllerName\" не найден.");
        require_once $controllerPath;
    }

    private function controllerInstance()
    {
        if (!class_exists($this->_controllerName, false))
            throw new \Exception("Класс \"$this->_controllerName\" не определен.");
        return new $this->_controllerName;
    }

    private function getActionParams(\ReflectionMethod $reflectionAction)
    {
        $routeData = Routing::getRouteData();
        $actionParams = $reflectionAction->getParameters();
//        return array_filter(array_map(function (ReflectionParameter $el) use($routeData) {
//            $result = $routeData[$el->getName()] ?? $_POST[$el->getName()] ?? null;
//            if (!is_null($result)) return $result;
//
//            if (!is_null($class = $el->getClass())) {
//                $result = ModelBinders::getBinders()[$class->getName()] ?? null;
//                if (!is_null($result)) return $result->bindModel();
//            }
//            if (!$el->isDefaultValueAvailable()) settype($result, $el->getType());
//            return $result;
//        }, $actionParams), function ($el) { return !is_null($el); });

        return array_map(function (\ReflectionParameter $el) use($routeData, $reflectionAction) {
            $reflectionType = $el->getType();
            $value = null;

            if (is_null($reflectionType) || $reflectionType->isBuiltin()) {
                $value = $routeData[$el->getName()]
                    ?? $_POST[$el->getName()]
                    ?? (isset($_FILES[$el->getName()]) ? file_get_contents($_FILES[$el->getName()]['tmp_name']) : null)
                    ?? ($el->isDefaultValueAvailable() ? $el->getDefaultValue() : null);

                if (is_null($value) && !is_null($reflectionType) && !$el->isDefaultValueAvailable())
                    throw new \Exception(
                        "Отсутствует обязательный параметр '" . $el->getName() . "' в методе действия " .
                        $reflectionAction->getDeclaringClass()->getShortName() . "::" . $reflectionAction->getShortName() . "()");
            } else {
                //$this->includeClassFile($reflectionType->getName());
                $value = ModelBinders::getBinders()[$class = $el->getClass()->getName()]->bindModel();
            }
            return $value;
        }, $actionParams);
    }




//    private function includeClassFile(string $typeName) {
//        include_once File::findFile("$typeName.php", "app");
//    }
}