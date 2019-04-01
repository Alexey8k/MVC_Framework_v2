<?php

namespace App\Core\Html;

class HtmlElement
{
    private $_name;

    private $_attributes;

    private static $selfClosers = ['input','img','hr','br','meta','link'];

    public function __construct(string $name, array $attributes = [])
    {
        $this->_name = $name;
        $this->_attributes = $attributes;
    }

    public function setAttr($attribute, $value = null)
    {
        if(!is_array($attribute))
        {
            $this->_attributes[$attribute] = $value;
        }
        else
        {
            $this->attributes = array_merge($this->_attributes, $attribute);
        }
    }

    public function toString()
    {
        return $this->build();
    }

    private function build()
    {
        $build = "<$this->_name";

        if(count($this->_attributes))
            foreach($this->_attributes as $key=>$value)
                if($key != 'text')
                    $build .= " $key=\"$value\"";

        if(!in_array($this->_name, HtmlElement::$selfClosers))
        {
            $text = $this->_attributes['text'];
            $build .= ">$text</$this->_name>";
        }
        else
            $build .= ' />';

        return $build;
    }
}