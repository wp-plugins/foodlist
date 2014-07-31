<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuSectionPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionTitleShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_section_title';
    }
    
    public function apply($attrs, $content = null)
    {
        /** @var MenuSectionPost $cur */
        $cur = Manager::getInstance()->get('cursection');
        $postData = $cur->getPostData();
        $title = $postData['title'];
        return $title;
    }
    
}