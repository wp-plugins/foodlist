<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller\Page;

use Artprima\Helper\ArrayProperties;
use Artprima\WordPress\Helper\Settings as SettingsHelper;
use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\BaseController;

class SettingsPageController extends BaseController
{
    
    /**
     * Inits controller
     */
    public function init()
    {
        add_action('init', array($this, 'processActions'));
        return $this;
    }
    
    public function processActions()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }
        
        if (!isset($_POST['foodlist']) || !is_array($_POST['foodlist'])) {
            return;
        }
        
        $data = new ArrayProperties($_POST['foodlist']);
        
        if (!wp_verify_nonce($data->get('nonce'), 'fl_settings')) {
            return;
        }
 
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $helper = SettingsHelper::getInstance('foodlist');
        do_action('foodlist_save_settings', $helper, $data);
        
        wp_redirect(admin_url('admin.php?page=foodlist-settings&message=success'));
    }
    
}