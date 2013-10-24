<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuExcerptShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_excerpt';
    }
    
    public function apply($attrs, $content = null)
    {
        $curmenu = Manager::getInstance()->get('curmenu');
        $postData = $curmenu->getPostData();
        $excerpt = $postData['excerpt'];
        return $excerpt;
    }
    
}