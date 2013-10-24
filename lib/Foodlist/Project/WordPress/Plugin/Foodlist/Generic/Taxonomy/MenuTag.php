<?php
/**
 * @version   $Id: MenuTag.php 346 2013-09-10 22:50:26Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Taxonomy;

use Artprima\WordPress\API\Wrapper\Generic\CustomTaxonomy;

class MenuTag extends CustomTaxonomy
{
    public function __construct()
    {
        $this->type = 'fl-menu-tag';
        
        $labels = array(
            'name'                       => _x('Tags', 'taxonomy general name', 'foodlist'),
            'singular_name'              => _x('Tag', 'taxonomy singular name', 'foodlist'),
            'search_items'               => __('Search Tags', 'foodlist'),
            'popular_items'              => __('Popular Tags', 'foodlist'),
            'all_items'                  => __('All Tags', 'foodlist'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Tag', 'foodlist'),
            'update_item'                => __('Update Tag', 'foodlist'),
            'add_new_item'               => __('Add New Tag', 'foodlist'),
            'new_item_name'              => __('New Tag Name', 'foodlist'),
            'separate_items_with_commas' => __('Separate tags with commas', 'foodlist'),
            'add_or_remove_items'        => __('Add or remove tags', 'foodlist'),
            'choose_from_most_used'      => __('Choose from the most used tags', 'foodlist'),
            'not_found'                  => __('No tags found.', 'foodlist'),
            'menu_name'                  => __('Tags', 'foodlist'),
        );
        
        $slug = apply_filters('fl_taxonomy_type_slug-'.$this->type, 'menu-tag');
        
        $this->args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => false,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array('slug' => $slug),
        );
        
        $this->objectType = 'fl-menu-item';
    }
    
}