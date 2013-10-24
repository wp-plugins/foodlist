<?php
/**
 * @version   $Id: Menu.php 339 2013-08-29 18:34:32Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\PostType;

use Artprima\WordPress\API\Wrapper\Generic\CustomPost;

class MenuSectionPostType extends CustomPost
{
    public function __construct()
    {
        $this->type = 'fl-menu-section';
        
        $labels = array(
            'name' => __('Menu Sections', 'foodlist'),
            'singular_name' => __('Menu Section', 'foodlist'),
            'add_new' => __('Add New', 'foodlist'),
            'add_new_item' => __('Add New Menu Section', 'foodlist'),
            'edit_item' => __('Edit Menu Section', 'foodlist'),
            'new_item' => __('New Menu Section', 'foodlist'),
            'all_items' => __('All Menu Sections', 'foodlist'),
            'view_item' => __('View Menu Section', 'foodlist'),
            'search_items' => __('Search Menu Sections', 'foodlist'),
            'not_found' => __('No menu sections found', 'foodlist'),
            'not_found_in_trash' => __('No menu sections found in Trash', 'foodlist'),
            'parent_item_colon' => '',
            'menu_name' => __('Menu Sections', 'foodlist'),
        );
        
        $slug = apply_filters('fl_post_type_slug-'.$this->type, 'menu-section');
        
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
        add_filter('manage_fl-menu-section_posts_columns', array($this, 'filterManagePostsColumns'));
        add_action('manage_fl-menu-section_posts_custom_column', array($this, 'onManagePostsCustomColumn'), 10, 2);  
    }
    
    public function filterManagePostsColumns($columns)
    {
        $new = array();
        foreach($columns as $key => $title) {
            $new[$key] = $title;
            if ($key == 'title') {
                $new['shortcode'] = __('Shortcode', 'foodlist');
                $new['items'] = __('Items', 'foodlist');
            }
        }
        return $new;  
    }
    
    private function getItemsCount($sectionId)
    {
        $order = get_post_meta($sectionId, '_fl_menu_items_order', true);
        
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
                echo '<input type="text" class="shortcode-in-list-table" value="[flmenu_section id=&quot;'.$postId.'&quot;]" readonly="readonly" onfocus="this.select();" />';
                break;
            case 'items':
                $url = admin_url('admin.php?page=foodlist-section-manager');
                $url = add_query_arg('fl-menu-section', $postId, $url);
                echo sprintf(__('<strong><a href="%s">%d</a></strong>', 'foodlist'), esc_url($url), $this->getItemsCount($postId));
                break;
        }
    }
    
    
}