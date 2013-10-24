<?php
/**
 * @version   $Id: Tag.php 339 2013-08-29 18:34:32Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller\Page;

use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\BaseController;

class TagPageController extends BaseController
{

    private function getAction()
    {
        if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
            return $_REQUEST['action'];

        if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
            return $_REQUEST['action2'];

        return false;
    }


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
        //$a = new \Foodlist\Project\WordPress\Plugin\Foodlist\Controller\Activation($this->getManager());
        //$a->setupDemoData();

        $action = $this->getAction();
        if ($action === false) {
            return;
        }


        
        if ($action == 'editedtag') {
            check_admin_referer('fl_edit_menu_tag', '_wpnonce_fl_edit_menu_tag');
            
            if (empty($_POST['fl_menu_tag']) || !is_array($_POST['fl_menu_tag'])) {
                return;
            }
            
            $termData = $_POST['fl_menu_tag'];
            
            $term = get_term($termData['term_id'], 'fl-menu-tag');
            if (!$term) {
                return;
            }
            wp_update_term($termData['term_id'], 'fl-menu-tag', array(
                'name' => $termData['name'],
                'slug' => $termData['slug'],
                'description' => $termData['description'],
            ));
            
            update_foodlist_menu_tag_meta($termData['term_id'], 'icon', $termData['icon']);
            
            wp_redirect(admin_url('admin.php?page=foodlist-menu-tags&message=3'));
        }

        if ($action == 'delete') {
            check_admin_referer('bulk-fl-menu-tags');
            
            if (empty($_GET['fl-menu-tag'])) {
                return;
            }

            $tags = (array)$_GET['fl-menu-tag'];

            foreach ($tags as $item) {
                //get_term((int)$item, 'fl-menu-tag');
                //delete_foodlist_menu_tag_meta($item);
                wp_delete_term($item, 'fl-menu-tag');
            }
            
            wp_redirect(admin_url('admin.php?page=foodlist-menu-tags&message=6'));
        }
        
        if ($action == 'addedtag') {
            check_admin_referer('fl_edit_menu_tag', '_wpnonce_fl_edit_menu_tag');
            
            if (empty($_POST['fl_menu_tag']) || !is_array($_POST['fl_menu_tag'])) {
                return;
            }
            
            $termData = $_POST['fl_menu_tag'];
            
            $termResult = wp_insert_term(
                $termData['name'],
                'fl-menu-tag',
                array(
                    'description' => $termData['description'],
                    'slug' => $termData['slug'],
                )
            );
            
            if (is_array($termResult)) {
                update_foodlist_menu_tag_meta($termResult['term_id'], 'icon', $termData['icon']);
                wp_redirect(admin_url('admin.php?page=foodlist-menu-tags&message=1'));
            } else {
                wp_redirect(admin_url('admin.php?page=foodlist-menu-tags&message=4'));
            }
        }
        
    }
    
}