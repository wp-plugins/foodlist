<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionExcerptShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_section_excerpt';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('cursection');
        $postData = $cur->getPostData();
        $excerpt = $postData['excerpt'];
        return $excerpt;
    }
    
}