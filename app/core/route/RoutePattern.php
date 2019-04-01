<?php

namespace App\Core\Route;

abstract class RoutePattern
{
    private static $_defaultConstraint = '[a-zA-Z\d%_-]+';

    // add for null value query param
    private static $_defaultConstraintNull = '[a-zA-Z\d%_-]*';

    public static function create(string $url, array $constraints)
    {
        RoutePattern::validateUrl($url);

        $defConstraint = RoutePattern::$_defaultConstraint;
        $defConstraintNull = RoutePattern::$_defaultConstraintNull;

        $replace = [
            '/ \{ [^{}]* \} /x' => function ($match) use ($constraints, $defConstraint) {
                $paramName = RoutePattern::validateParamName($match[0]);
                return "(?'$paramName' " . ($constraints[$paramName] ?? $defConstraint) . " )";
            },
            '/ \/ /x' => function ($match) {
                return ' \\' . $match[0] . ' ';
            }
        ];
        // был добавлен фрагмент \/?
        return '/^ \/' . preg_replace_callback_array($replace, $url)
            . "\/? (?| \? (?'queryParams' (?<=\?) $defConstraint = $defConstraintNull (?| & (?(?<=&) $defConstraint = $defConstraintNull ) )* ) )? $/x";
    }

    private static function validateUrl(string $url)
    {
        if (preg_match('/ ^[~\/] | \?+ /x', $url))
            throw new \Exception("URL-адрес маршрута не может начинаться с символа '/' или '~', и он не может содержать символ '?'.");

        if (empty($url)) return;

        $segments = explode('/', $url);

        foreach ($segments as $key => $segment)
        {
            if (empty($segment) && $key != count($segments) - 1)
                throw new \Exception("Символ разделителя URL-адреса маршрута '/' не может появляться последовательно. Он должен быть разделен либо параметром, либо литеральным значением.");
            elseif (!preg_match('/^ (?> [^{}]* (?> \{ [^{}]* \} )? )* $/x', $segment))
                throw new \Exception("В этом сегменте пути есть неполный параметр: \"$segment\". Убедитесь, что каждый символ '{' имеет соответствующий символ '}'.");
            elseif (preg_match('/ \}\{ /x', $segment))
                throw new \Exception("Сегмент пути не может содержать два последовательных параметра. Они должны быть разделены символом '/' или строкой литерала.");
        }
    }

    private static function validateParamName(string $param)
    {
        preg_match("/ (?<=\{) (?: (?'invalid' .* [{}\/?]+ .* | [{}?\/]* ) | (?'valid' [^{}\/?]+ )) (?=\}) /x", $param, $paramName);
        if (empty($paramName['valid']))
            throw new \Exception("Имя параметра маршрута '" . $paramName['invalid'] . "' недействительно. Имена параметров маршрута должны быть не пустыми и не содержать этих символов: «{», «}», «/», «?»");
        return $paramName['valid'];
    }
}