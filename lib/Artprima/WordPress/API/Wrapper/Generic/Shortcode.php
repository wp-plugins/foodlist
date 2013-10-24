<?php

namespace Artprima\WordPress\API\Wrapper\Generic;

abstract class Shortcode
{
    
    protected $tag;
    
    public function init()
    {
        add_shortcode($this->getTag(), $this->getFunction());
    }
    
    public function getTag()
    {
        return $this->tag;
    }
    
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }
    
    public function getFunction()
    {
        return array($this, 'handle');
    }
    
    abstract public function handle($attrs);
    
}
