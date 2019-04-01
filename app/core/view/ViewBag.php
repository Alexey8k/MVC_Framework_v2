<?php

namespace App\Core\View;

class ViewBag implements \ArrayAccess
{
    private $_view;

    public function __construct(View $view)
    {
        $this->_view = $view;
    }

    public function offsetExists($offset)
    {
        return isset($this->_view->viewBag[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_view->viewBag[$offset]) ? $this->_view->viewBag[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_view->viewBag[] = $value;
        } else {
            $this->_view->viewBag[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->_view->viewBag[$offset]);
    }
}