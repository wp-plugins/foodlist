<?php

namespace Artprima\WordPress\API\Wrapper\Generic;

/**
 * Class CustomPost
 *
 * @author Denis Voytyuk <ask@artprima.cz>
 *
 * @package Artprima\WordPress\API\Wrapper\Generic
 */
class CustomPost
{
    /**
     * Post type. (max. 20 characters, can not contain capital letters or spaces) 
     * 
     * The following post types are reserved and used by WordPress already.
     * 
     *     post
     *     page
     *     attachment
     *     revision
     *     nav_menu_item 
     * 
     * In addition, the following post types should not be used as they interfere with other WordPress functions.
     * 
     *     action
     *     order 
     * 
     * @var string 
     */
    protected $type;
    
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
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->args = array();
    }
    
    /**
     * Set argument for the post type setup
     * 
     * @param string $name
     * @param mixed $value
     * @return \Artprima\WordPress\API\Wrapper\Generic\CustomPost
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
     * @return \Artprima\WordPress\API\Wrapper\Generic\CustomPost
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
        return $this;
    }
    
    /**
     * Sets up wordpress init hook
     * 
     * @return \Artprima\WordPress\API\Wrapper\Generic\CustomPost
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
        register_post_type($this->type, $this->args);
    }
}
