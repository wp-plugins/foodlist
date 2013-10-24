<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemIdShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item_id';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('curitem');
        $postData = $cur->getPostData();
        $id = $postData['id'];
        return $id;
    }
    
}