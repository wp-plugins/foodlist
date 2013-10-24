<?php

namespace Artprima\Text;

abstract class Shortcode
{
    
    protected $tag;
    
    /**
     *
     * @var ShortcodeManager 
     */
    protected $manager;

    protected $args;

    public function __construct($args = array())
    {
        $this->args = $args;
    }

    /**
     * 
     * @param \Artprima\Text\ShortcodeManager $manager
     */
    public function setManager(ShortcodeManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * 
     * @return ShortcodeManager
     */
    public function getManager()
    {
        return $this->manager;
    }
    
    public function applyAllDeeper($content)
    {
        if ($this->manager) {
            $content = $this->manager->applyShortcodes($content);
        }
        return $content;
    }
    
    public function getTag()
    {
        return $this->tag;
    }
    
    abstract function apply($attrs, $content = null);
    
}