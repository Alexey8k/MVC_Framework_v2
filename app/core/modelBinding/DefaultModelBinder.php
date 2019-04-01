<?php

namespace App\Core\ModelBinding;

use App\Core\File;
use App\Core\Route\Routing;

class DefaultModelBinder implements IModelBinder
{
    private $_classInstance;

    public function __construct(string $className)
    {
        //DefaultModelBinder::includeFile("$className.php");
        $this->_classInstance = $this->getClassInstance($className);
    }

    public function bindModel()
    {
        return $this->_classInstance;
    }

//    private function includeFile($fileName)
//    {
//        if (is_null($filePath = File::findFile($fileName, 'app')))
//            throw new \Exception("Неудалось найти файл: '$fileName'");
//        require_once $filePath;
//    }

    private function getClassInstance($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        $classInstance = $reflectionClass->newInstanceWithoutConstructor();
        $propertyNames = $this->getProperties($reflectionClass);
        $routeData = Routing::getRouteData();
        foreach ($propertyNames as $propertyName)
            $classInstance->$propertyName = $routeData[$propertyName] ?? $_POST[$propertyName] ?? null;

        return $classInstance;
    }

    private function getProperties(\ReflectionClass $reflectionClass) : array
    {
        $docComment = $reflectionClass->getDocComment();
        $propertyNames = [];
        $needle = '@property';

        while ($pos = strpos($docComment,$needle)){
            $docComment = substr($docComment, $pos + strlen($needle) + 1);
            $propertyNames[] = trim(explode(' ', strstr($docComment, '*', true))[1]);
        }

        return $propertyNames;
    }
}