<?php

namespace App\Core\Repository;

use App\Core\BaseModel;

/**
 * Class Parameter
 * @property string type
 * @property null value
 */
class Parameter extends BaseModel
{
    public function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    protected $type;

    protected $value;

    public function & getValueByRef() {
        return $this->value;
    }
}