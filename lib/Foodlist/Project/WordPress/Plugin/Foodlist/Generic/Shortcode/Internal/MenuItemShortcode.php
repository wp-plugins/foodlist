<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('curitemview');
        $result = sprintf('[flmenu_item id="%d" instance="%d"]', $cur->getId(), $cur->getInstanceId());
        
        return $result;
    }
    
}