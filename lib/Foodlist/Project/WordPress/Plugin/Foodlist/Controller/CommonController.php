<?php
/**
 * @version   $Id: Common.php 347 2013-09-21 09:21:56Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\PostType\MenuItemPostType as MenuItemPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\PostType\MenuSectionPostType as MenuSectionPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\PostType\MenuPostType as MenuPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\PrintMenuShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Taxonomy\MenuTag;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\MenuItemShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\MenuSectionShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\MenuShortcode;

class CommonController extends BaseController
{
    
    /**
     * Inits controller
     */
    public function init()
    {
        load_plugin_textdomain('foodlist', false, $this->manager->getPluginRelDir().'/locale');

        $this->registerPostTypes();
        $this->registerTaxonomyTypes();
        $this->registerShortcodes();

        add_action('init', array($this, 'onInit'));
        return $this;
    }
    
    /**
     * @usage private
     */
    public function onInit()
    {
        add_image_size('fl-menu-tag-icon', 16, 16, true);
        add_image_size('fl-menu-item-thumb', 100, 100, true);
    }
    
    /**
     * Registers post types
     * 
     * @return CommonController
     */
    private function registerPostTypes()
    {
        $customPosts = array();
        $customPosts['menu-item'] = $menu = new MenuItemPost();
        $menu->setupRegistrationHook();
        $customPosts['menu-section'] = $menu = new MenuSectionPost();
        $menu->setupRegistrationHook();
        $customPosts['menu'] = $menu = new MenuPost();
        $menu->setupRegistrationHook();
        $this->getManager()->set('custom_posts', $customPosts);
        return $this;
    }
    
    /**
     * Registers taxonomy types
     * 
     * @return CommonController
     */
    private function registerTaxonomyTypes()
    {
        $taxonomies = array();
        $taxonomies['fl-menu-tag'] = $menuTag = new MenuTag();
        $menuTag->setupRegistrationHook();
        $this->getManager()->set('taxonomies', $taxonomies);
        return $this;
    }
    
    /**
     * Registers shortcodes
     * 
     * @return CommonController
     */
    private function registerShortcodes()
    {
        $sc = new MenuShortcode;
        $sc->init();
        $sc = new MenuItemShortcode;
        $sc->init();
        $sc = new MenuSectionShortcode;
        $sc->init();
        $sc = new PrintMenuShortcode;
        $sc->init();
        return $this;
    }
}