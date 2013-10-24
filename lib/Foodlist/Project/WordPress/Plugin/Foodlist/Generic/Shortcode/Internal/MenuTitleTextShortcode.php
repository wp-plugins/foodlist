<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuTitleTextShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_title_text';
    }
    
    public function apply($attrs, $content = null)
    {
        $curmenu = Manager::getInstance()->get('curmenu');
        $postData = $curmenu->getPostData();
        $title = $postData['title'];
        return $title;
    }
    
}