<?php

namespace App\Core\ModelBinding;

class ModelBinderDictionary implements \ArrayAccess
{
    /**
     * @var IModelBinder[]
     */
    private $_binders = [];

    //region implement ArrayAccess
    public function offsetExists($offset)
    {
        return true; // isset($this->_binders[$offset]);
    }

    public function offsetGet($offset) //: IModelBinder
    {
        return isset($this->_binders[$offset]) ? $this->_binders[$offset] : (new DefaultModelBinder($offset));
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_binders[] = $value;
        } else {
            $this->_binders[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->_binders[$offset]);
    }
    //endregion
}