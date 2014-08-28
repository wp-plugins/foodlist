<?php

namespace Artprima\WordPress\Helper;

//use Artprima\Helper\ArrayProperties;

class Settings
{
    protected static $instances = array();
    protected $settings;
    protected $optionName;

    /**
     * 
     * @param string $optionName
     * @return \Artprima\WordPress\Helper\Settings
     */
    public static function getInstance($optionName)
    {
        if (empty(self::$instances[$optionName])) {
            self::$instances[$optionName] = new self($optionName);
        }
        
        return self::$instances[$optionName];
    }
    
    protected function __construct($optionName)
    {
        $this->optionName = $optionName;
        $this->settings = get_option($optionName);
        if (!is_array($this->settings)) {
            $this->settings = array();
        }
    }
    
    public function get($name, $default=null)
    {
        return array_key_exists($name, $this->settings) ? $this->settings[$name] : $default;
    }
    
    public function set($name, $val)
    {
        $this->settings[$name] = $val;
        return $this;
    }
    
    public function delete($name)
    {
        if (array_key_exists($name, $this->settings)) {
            unset($this->settings[$name]);
        }
        return $this;
    }
    
    public function save()
    {
        update_option($this->optionName, $this->settings);
        return $this;
    }
    
    public function erase()
    {
        delete_option($this->optionName);
        $this->settings = array();
        return $this;
    }
}