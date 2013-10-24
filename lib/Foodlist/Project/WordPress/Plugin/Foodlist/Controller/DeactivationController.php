<?php
/**
 * @version   $Id$
 * @package   Foodlist
 * @copyright Copyright (C) 2013 Foodlist Team / http://artprima.eu/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Artprima\WordPress\Helper\Settings as SettingsHelper;

class DeactivationController extends BaseController
{
    
    /**
     * Inits controller
     */
    public function init()
    {
        $helper = SettingsHelper::getInstance('foodlist');
        if ($helper->get('delete_data_on_plugin_deactivate')) {
            $helper->erase();
            delete_option('foodlist_first_start');
            delete_option('foodlist_demo_data_ids');
            $this->dropTables();
            $this->dropPosts();
            $this->dropTerms();
        }
        return $this;
    }
    
    public function dropTables()
    {
        global $wpdb;
        $wpdb->query('
            DROP TABLE IF EXISTS `'.$wpdb->get_blog_prefix().'foodlist_menu_tagmeta`
        ');
    }

    private function dropPostsByType($type)
    {
        
        $query = new \WP_Query(array(
            'post_type' => $type,
            'posts_per_page' => -1,
        ));
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                wp_delete_post(get_the_ID(), true);
            }
        }
        wp_reset_postdata();
    }
    
    public function dropPosts()
    {
        foreach (array('fl-menu', 'fl-menu-section', 'fl-menu-item') as $type)
        {
            $this->dropPostsByType($type);
        }
    }
    
    public function dropTerms()
    {
        $terms = get_terms(array(
           'fl-menu-tag' ,
        ), array(
            'hide_empty'    => false,
        ));
        
        foreach ($terms as $term)
        {
            wp_delete_term($term->term_id, $term->taxonomy);
        }
    }
    
}