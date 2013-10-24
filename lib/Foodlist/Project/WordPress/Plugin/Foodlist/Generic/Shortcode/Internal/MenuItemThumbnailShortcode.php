<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemThumbnailShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item_thumbnail';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('curitem');
        $postData = $cur->getPostData();
        $id = $postData['id'];
        
        $result = '';
        if (has_post_thumbnail($id)) {
            $result = get_the_post_thumbnail($id, 'fl-menu-item-thumb', array('class' => 'alignleft fl-menu-item-thumb'));
        }
        
        return $result;
    }
    
}