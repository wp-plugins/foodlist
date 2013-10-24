<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItemPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemInstanceShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item_instance';
    }
    
    public function apply($attrs, $content = null)
    {
        /* @var $cur MenuItemPost */
        $cur = Manager::getInstance()->get('curitem');
        $id = $cur->getInstanceId();
        return $id;
    }
    
}