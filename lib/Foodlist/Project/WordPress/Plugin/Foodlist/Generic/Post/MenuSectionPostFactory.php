<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuSectionView as MenuSectionView;

class MenuSectionPostFactory
{
    
    protected $query;
    
    public static function getOne($id)
    {
        $query = new \WP_Query(array(
            'post_type' => 'fl-menu-section',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'order' => 'ASC',
            'orderby' => 'title',
            'p' => $id,
        ));
        
        if ($query->have_posts()) {
            $query->the_post();
            
            $menuItem = new MenuSectionPost(get_the_ID());
            $menuItem->preparePostData();
        }
        
        wp_reset_postdata();
        
        return $menuItem;
    }
    
    public function __construct()
    {
        $this->query = new \WP_Query(array(
            'post_type' => 'fl-menu-section',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'title',
        ));
    }
    
    public function havePosts()
    {
        return $this->query->have_posts();
    }
    
    public function getView()
    {
        $this->query->the_post();
        $menuSectionView = new MenuSectionView();
        $menuSectionView->setId(get_the_ID());
        return $menuSectionView;
    }
    
    public function getPost()
    {
        $this->query->the_post();
        $menuItem = new MenuSectionPost(get_the_ID());
        $menuItem->preparePostData();
        return $menuItem;
    }
    
    public function finish()
    {
        wp_reset_postdata();
    }
    
}
