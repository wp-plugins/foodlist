<?php
/**
 * @version   $Id: Menu.php 339 2013-08-29 18:34:32Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\PostType;

use Artprima\WordPress\API\Wrapper\Generic\CustomPost;

use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox;

class MenuItemPostType extends CustomPost
{
    public function __construct()
    {
        $this->type = 'fl-menu-item';

        $labels = array(
            'name' => __('Menu Items', 'foodlist'),
            'singular_name' => __('Menu Item', 'foodlist'),
            'add_new' => __('Add New', 'foodlist'),
            'add_new_item' => __('Add New Menu Item', 'foodlist'),
            'edit_item' => __('Edit Menu Item', 'foodlist'),
            'new_item' => __('New Menu Item', 'foodlist'),
            'all_items' => __('All Menu Items', 'foodlist'),
            'view_item' => __('View Menu Item', 'foodlist'),
            'search_items' => __('Search Menu Items', 'foodlist'),
            'not_found' => __('No menu items found', 'foodlist'),
            'not_found_in_trash' => __('No menu items found in Trash', 'foodlist'),
            'parent_item_colon' => '',
            'menu_name' => __('Menu Items', 'foodlist'),
        );

        $slug = apply_filters('fl_post_type_slug-'.$this->type, 'menu-item');

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
        
        // metaboxes
        $metabox = new Metabox\MenuItem\PriceMetabox();
        $metabox->init();
        $metabox = new Metabox\MenuItem\TagsMetabox();
        $metabox->init();
        
        $this->registerColumns();
    }
    
    public function registerColumns()
    {
        add_filter('manage_fl-menu-item_posts_columns', array($this, 'filterManagePostsColumns'));
        add_action('manage_fl-menu-item_posts_custom_column', array($this, 'onManagePostsCustomColumn'), 10, 2);  
    }
    
    public function filterManagePostsColumns($columns)
    {
        $new = array();
        foreach($columns as $key => $title) {
            $new[$key] = $title;
            if ($key == 'title') {
                $new['shortcode'] = __('Shortcode', 'foodlist');
            }
        }
        return $new;  
    }
    
    public function onManagePostsCustomColumn($columnName, $postId)
    {
        switch ($columnName) {
            case 'shortcode':
                echo '<input type="text" class="shortcode-in-list-table" value="[flmenu_item id=&quot;'.$postId.'&quot;]" readonly="readonly" onfocus="this.select();" />';
                break;
        }
    }

}
