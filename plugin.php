<?php
/**
 * @version   $Id$
 * @package   Foodlist
 * @copyright Copyright (C) 2013 Artprima / http://artprima.eu/. All rights reserved.
 */

/*
Plugin Name: Foodlist
Plugin URI: http://foodlist.demo.5x5.cz/
Description: Easily build your restaurant/café menus within the WordPress admin.
Author: Artprima
Author URI: http://artprima.eu/
Version: 1.1
*/

define('FOODLIST_VERSION', '1.1');
define('FOODLIST_MIN_PHP_VERSION', '5.3.0');
define('FOODLIST_MIN_WP_VERSION', '3.4.0');

if (version_compare(PHP_VERSION, FOODLIST_MIN_PHP_VERSION, '<')) {
    add_action('admin_notices', 'foodlist_lib_admin_notice_php');
    function foodlist_admin_notice_php() {
         echo sprintf(
            __('<div class="error"><p><strong>Food List plugin error</strong>: php version %1$s detected, while %2$s or later expected! This plugin will not work, please upgrade.</p></div>', 'foodlist'),
            PHP_VERSION, FOODLIST_MIN_PHP_VERSION);
    }
} elseif (version_compare(get_bloginfo('version'), FOODLIST_MIN_WP_VERSION, '<')) {
    add_action('admin_notices', 'foodlist_lib_admin_notice_wp');
    function foodlist_admin_notice_wp() {
        echo sprintf(__('<div class="error"><p><strong>Food List plugin error</strong>: WordPress version %1$s detected, while %2$s or later expected!</p></div>', 'foodlist'),
            get_bloginfo('version'), FOODLIST_MIN_WP_VERSION
        );
    }
} else {
    require_once dirname(__FILE__).'/meta.php';
    require_once dirname(__FILE__).'/loader.php';
}
