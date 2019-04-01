<?php

namespace App\Core\Repository;

abstract class StoredProcedure
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isFunction;

    /**
     * @var Parameter[]
     */
    protected $params = [];

    /**
     * @var callable[]
     */
    protected $rowMappers = [];

    protected const DEFAULT_KEY = 'ResultSet';

    public function __construct(string $name, bool $isFunction = false)
    {
        $this->name = $name;
        $this->isFunction = $isFunction;
    }

    protected function returningResultSet(string $paramName, callable $rowMapper = null) {
        $this->rowMappers[$paramName] = $rowMapper;
    }

    protected function addParam(string $type, $value) {
        $this->params[] = new Parameter($type, $value);
    }

    protected function strStoredProcedure() {
        return ($this->isFunction ? 'SELECT' : 'CALL')
            . " `$this->name`(" . implode(',', array_fill(0, count($this->params), '?')) . ")";
    }

    protected function getDefaultKey(array $keys, int $postfix = null) : string {
        $defaultKey = StoredProcedure::DEFAULT_KEY . $postfix;
        return key_exists($defaultKey, $keys)
            ? $this->getDefaultKey($keys, is_null($postfix) ? 1 : $postfix++)
            : $defaultKey;
    }

}