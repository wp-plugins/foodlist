<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuIdShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_id';
    }
    
    public function apply($attrs, $content = null)
    {
        $curmenu = Manager::getInstance()->get('curmenu');
        /* @var $curmenu MenuPost */
        $postData = $curmenu->getPostData();
        $id = $postData['id'];
        return $id;
    }
    
}