<?php

namespace Artprima\Helper;

class ArrayProperties {
    
    private $arr;
    
    public function __construct(array $arr)
    {
        if (is_array($arr)) {
            $this->arr = $arr;
        } else {
            trigger_error(sprintf('Variable of type %s passed while array was expected', gettype($arr)), E_USER_NOTICE);
            $this->arr = array();
        }
    }
    
    public function get($name, $default=null)
    {
        return array_key_exists($name, $this->arr) ? $this->arr[$name] : $default;
    }
    
    public function getAll()
    {
        return $this->arr;
    }
    
}