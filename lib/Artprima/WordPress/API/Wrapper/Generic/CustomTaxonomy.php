<?php

namespace Artprima\WordPress\API\Wrapper\Generic;

class CustomTaxonomy
{
    /**
     * The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces) and not more than 32 characters long (database structure restriction).
     * 
     * @var string 
     */
    protected $type;
    
    /**
     * Name of the object type for the taxonomy object. Object-types can be built-in Post Type or any Custom Post Type that may be registered. 
     * 
     * @var string|array
     */
    protected $objectType;
    
    /**
     * An array of arguments.
     * 
     * @var string 
     */
    protected $args;
    
    /**
     * Constructor
     * 
     * @param string $type Post type. (max. 20 characters, can not contain capital letters or spaces) 
     * @param string|array $objectType Name of the object type for the taxonomy object. Object-types can be built-in Post Type or any Custom Post Type that may be registered. 
     */
    public function __construct($type, $objectType)
    {
        $this->type = $type;
        $this->objectType = $objectType;
        $this->args = array();
    }
    
    /**
     * Set argument for the post type setup
     * 
     * @param string $name
     * @param mixed $value
     * @return \Artprima\WordPress\API\Wrapper\CustomPost
     */
    public function setArg($name, $value)
    {
        $this->args[$name] = $value;
        return $this;
    }
    
    /**
     * Set arguments for the post type setup
     * 
     * @param array $args
     * @return \Artprima\WordPress\API\Wrapper\CustomPost
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }
    
    /**
     * Sets up wordpress init hook
     * 
     * @return \Artprima\WordPress\API\Wrapper\CustomPost
     */
    public function setupRegistrationHook()
    {
        add_action('init', array($this, 'onInit'));
        return $this;
    }
    
    /**
     * @usage private
     */
    public function onInit()
    {
        register_taxonomy($this->type, $this->objectType, $this->args);
    }
}
