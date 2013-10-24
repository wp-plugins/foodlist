<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuSectionPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionInstanceShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_section_instance';
    }
    
    public function apply($attrs, $content = null)
    {
        /* @var $cur MenuSectionPost */
        $cur = Manager::getInstance()->get('cursection');
        $id = $cur->getInstanceId();
        return $id;
    }
    
}