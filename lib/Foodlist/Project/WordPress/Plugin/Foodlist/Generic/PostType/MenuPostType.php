<?php
/**
 * @version   $Id: Menu.php 340 2013-09-08 10:49:31Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\PostType;

use Artprima\WordPress\API\Wrapper\Generic\CustomPost;

class MenuPostType extends CustomPost
{
    public function __construct()
    {
        $this->type = 'fl-menu';
        
        $labels = array(
            'name' => __('Menus', 'foodlist'),
            'singular_name' => __('Menu', 'foodlist'),
            'add_new' => __('Add New', 'foodlist'),
            'add_new_item' => __('Add New Menu', 'foodlist'),
            'edit_item' => __('Edit Menu', 'foodlist'),
            'new_item' => __('New Menu', 'foodlist'),
            'all_items' => __('All Menus', 'foodlist'),
            'view_item' => __('View Menu', 'foodlist'),
            'search_items' => __('Search Menus', 'foodlist'),
            'not_found' => __('No menus found', 'foodlist'),
            'not_found_in_trash' => __('No menus found in Trash', 'foodlist'), 
            'parent_item_colon' => '',
            'menu_name' => __('Menus', 'foodlist'),
        );
        
        $slug = apply_filters('fl_post_type_slug-'.$this->type, 'menu');
        
        $this->args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'rewrite' => array('slug' => $slug),
            'capability_type' => 'post',
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array( 'title', 'thumbnail', 'excerpt' )
        );
        
        $this->registerColumns();
    }
    
    public function registerColumns()
    {
        add_filter('manage_fl-menu_posts_columns', array($this, 'filterManagePostsColumns'));
        add_action('manage_fl-menu_posts_custom_column', array($this, 'onManagePostsCustomColumn'), 10, 2);  
    }
    
    public function filterManagePostsColumns($columns)
    {
        $new = array();
        foreach($columns as $key => $title) {
            $new[$key] = $title;
            if ($key == 'title') {
                $new['shortcode'] = __('Shortcode', 'foodlist');
                $new['sections'] = __('Sections', 'foodlist');
            }
        }
        return $new;  
    }
    
    private function getSectionCount($sectionId)
    {
        $order = get_post_meta($sectionId, '_fl_menu_sections_order', true);
        
        if (empty($order)) {
            return 0;
        }

        $items = explode(',', $order);
        
        return count($items);
    }
    
    public function onManagePostsCustomColumn($columnName, $postId)
    {
        switch ($columnName) {
            case 'shortcode':
                echo '<input type="text" class="shortcode-in-list-table" value="[flmenu id=&quot;'.$postId.'&quot;]" readonly="readonly" onfocus="this.select();" />';
                break;
            case 'sections':
                $url = admin_url('admin.php?page=foodlist-menu-manager');
                $url = add_query_arg('fl-menu', $postId, $url);
                echo sprintf(__('<strong><a href="%s">%d</a></strong>', 'foodlist'), esc_url($url), $this->getSectionCount($postId));
                break;
        }
    }
    
    
}