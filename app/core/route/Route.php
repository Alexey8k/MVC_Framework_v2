<?php

namespace App\Core\Route;

class Route
{
    private $_pattern;
    private $_defaults;
    private $_url;
    private $_urlParams;
    private $_constraints;

    public function __construct(string $url, array $defaults = [], array $constraints = [])
    {
        $this->_pattern = RoutePattern::create($url, $constraints);
        $this->_defaults = $defaults;
        $this->_url = $url;
        $this->_urlParams = $this->getUrlParams($url);
        $this->_constraints = $constraints;
    }

    public function getRouteData(string $url)
    {
        $params = $this->match($url);
        return  $params !== null
            ? array_merge($params, $this->_defaults)
            : null;
    }

    public function getUrl(array $routeValues)
    {
//        if (count($this->_urlParams) == 0)
//            return null;
        $exists = array_intersect_key($routeValues, array_merge($this->_defaults, $this->_urlParams));
        $noExists = array_diff_key($routeValues, $exists);

        if (array_diff_key($this->_urlParams, $exists))
            return null;

        foreach ($exists as $key => $value) {
            $data = $this->_defaults[$key] ?? null;
            $constraint = $this->_constraints[$key] ?? null;

            if (!array_key_exists($key, $this->_urlParams)
                && (!isset($data) || $data != $value)
                || (isset($constraint) && !preg_match("/$constraint/", $value))
            )
                return null;
        }

        $url = '/' . preg_replace_callback('/\{ [^{}]* \}/x', function ($match) use($routeValues) {
            return $routeValues[trim($match[0], '{}')];
        }, $this->_url);

        $ch = '?';

        foreach ($noExists as $key => $value) {
            $url .= "$ch$key=" . rawurlencode($value);
            if ($ch == '?') $ch = '&';
        }

        return $url;
    }

    private function getUrlParams(string $url)
    {
        preg_match_all('/(?<=\{) [^{}]+ (?=\})/x', $url, $match);
        return array_fill_keys(array_values($match[0]), null);
    }

    private function match(string $url)
    {
        $params = null;
        if (!preg_match($this->_pattern, $url, $params)) return null;

        $queryParams = $this->parseQueryParams($params['queryParams'] ?? '');
        $params = array_filter($params, function ($key) { return is_string($key) && $key != 'queryParams'; }, ARRAY_FILTER_USE_KEY);

        return is_null($queryParams) ? $params : array_merge($params, $queryParams);
    }

    private function parseQueryParams(string $strQueryParams)
    {
        if (is_null($strQueryParams)) return null;
        $queryParams = null;
        parse_str($strQueryParams, $queryParams);
        return $queryParams;
    }
}