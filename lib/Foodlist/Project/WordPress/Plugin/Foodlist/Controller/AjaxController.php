<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\Manager\Section;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\Manager\Menu;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\SettingsAjax;

class AjaxController
{
    
    public function __construct()
    {
        add_action('wp_ajax_foodlist_section', array(new Section, 'handle'));
        add_action('wp_ajax_foodlist_menu', array(new Menu, 'handle'));
        
        add_action('wp_ajax_foodlist_settings', array(new SettingsAjax, 'handle'));
    }
    
}