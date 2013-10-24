<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionIdShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_section_id';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('cursection');
        $postData = $cur->getPostData();
        $id = $postData['id'];
        return $id;
    }
    
}