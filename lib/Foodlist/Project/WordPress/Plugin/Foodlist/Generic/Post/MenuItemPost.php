<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post;

class MenuItemPost extends BasePost
{
    protected $instanceId;
    protected $priceData;
    protected $tags;
    
    public function __construct($postId)
    {
        $this->postId = $postId;
        $this->priceData = get_post_meta($postId, '_fl_price_data', true);

        if (!is_array($this->priceData)) {
            $this->priceData = array();
        }
    }
    
    public function getPrice($group = 'default', $currency = 'default')
    {
        return isset($this->priceData[$group][$currency]) ? $this->priceData[$group][$currency] : '';
    }
    
    public function setPrice($price, $group = 'default', $currency = 'default')
    {
        $this->priceData[$group][$currency] = $price;
        update_post_meta($this->postId, '_fl_price_data', $this->priceData);
        return $this;
    }
    
    public function getTags()
    {
        if ($this->tags === null) {
            $this->tags = wp_get_post_terms($this->postId, 'fl-menu-tag');
        }
        return $this->tags;
    }

    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    public function getInstanceId()
    {
        return $this->instanceId;
    }
}
