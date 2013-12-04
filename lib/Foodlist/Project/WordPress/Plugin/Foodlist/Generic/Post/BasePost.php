<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post;

class BasePost
{
    protected $postData;
    protected $postId;

    public function getPostData()
    {
        return $this->postData;
    }
    
    public function getId()
    {
        return $this->postId;
    }
    
    public function setId($postId)
    {
        $this->postId = $postId;
        return $this;
    }

    private function getTheExcerpt() {
        $post = get_post();

        if (post_password_required()) {
            return __('There is no excerpt because this is a protected post.', 'foodlist');
        }

        return $post->post_excerpt;
    }


    public function preparePostData()
    {
        $id = get_the_ID();
        $title = get_the_title();
        //$price = $this->getPrice();
        //$tags = $this->getTags();
        $excerpt = $this->getTheExcerpt();
        $content = get_the_content();
            
        $this->postData = compact('id', 'title', 'excerpt', 'content');
        
        return $this;
    }
    
    
}