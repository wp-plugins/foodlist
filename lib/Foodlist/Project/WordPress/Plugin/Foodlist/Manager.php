<?php
/**
 * @version   $Id: Manager.php 349 2013-09-21 22:09:02Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist;

use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\AdminController as AdminController;
use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\FrontendController as FrontendController;
use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\CommonController as CommonController;

class Manager
{
    
    /**
     * Manager instance 
     * 
     * @var Manager
     */
    private static $manager;
    
    /**
     * Plugin url
     * 
     * @var string
     */
    private $pluginUrl;
    
    /**
     * Plugin dir
     * 
     * @var string
     */
    private $pluginDir;

    /**
     * Plugin rel dir for use in load_plugin_textdomain()
     *
     * @var string
     */
    private $pluginRelDir;
    
    /**
     * Plugin initialization state
     * 
     * @var boolean 
     */
    private $inited = false;

    /**
     * Container for different shared values
     * 
     * @var array 
     */
    private $container = array();
    
    /**
     * Constructor
     * 
     * Protected to avoid multiple instances
     */
    protected function __construct()
    {
        ;
    }
    
    /**
     * Get manager instance
     * 
     * @return Manager manager instance
     */
    public static function getInstance()
    {
        if (self::$manager === null) {
            self::$manager = new self;
        }
        
        return self::$manager;
    }
    
    /**
     * Gets plugin url
     * 
     * @return string plugin url
     */
    public function getPluginUrl()
    {
       return $this->pluginUrl;
    }
    
    /**
     * Gets plugin dir
     * 
     * @return string plugin dir
     */
    public function getPluginDir()
    {
        return $this->pluginDir;
    }

    public function getPluginRelDir()
    {
        return $this->pluginRelDir;
    }

    /**
     * Registeres an actication hook for the plugin
     */
    public function registerActivationHooks()
    {
        //$scm = new \Artprima\Text\ShortcodeManager();
        //$sc = new \Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\TestShortcode('tttt');
        //$scm->registerShortcode($sc);
        //$sc = new \Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\XxxShortcode('xxx');
        //$scm->registerShortcode($sc);
        /*
        var_dump($scm->applyShortcodes('
            ASda fs dfasd234234 sdfasdf asdf [tttt] [xxx]sdfasdfasdfasdf[/xxx] [/tttt]
        '));die();
        */
        
        
        register_activation_hook('foodlist/plugin.php', array(new Controller\ActivationController($this), 'init'));
        register_deactivation_hook('foodlist/plugin.php', array(new Controller\DeactivationController($this), 'init'));
    }
    
    /**
     * Inits the plugin
     * 
     * @param string $url
     * @param srting $dir
     * 
     * @return Manager manager instance
     */
    public function init($url, $dir)
    {
        if ($this->inited) {
            return $this;
        }
        
        $this->pluginUrl = $url;
        $this->pluginDir = $dir;
        $this->pluginRelDir = basename($dir);

        // initialization calls
        $this->initProperController();

        $this->inited = true;
        return $this;
    }

    public function set($name, $val)
    {
        $this->container[$name] = $val;
        return $this;
    }
    
    public function get($name, $default=null)
    {
        return isset($this->container[$name]) ? $this->container[$name] : $default;
    }
    
    /**
     * Inits appropriate controller depending on the case
     */
    private function initProperController()
    {
        $commons = new CommonController($this);
        $commons->init();
        if (is_admin()) {
            $ctrl = new AdminController($this);
            $ctrl->init();
            
            if (defined('DOING_AJAX') && DOING_AJAX) {
                $ctrl = new Controller\AjaxController($this);
            }
        } else {
            $ctrl = new FrontendController($this);
            $ctrl->init();
        }
    }
    
    
}
