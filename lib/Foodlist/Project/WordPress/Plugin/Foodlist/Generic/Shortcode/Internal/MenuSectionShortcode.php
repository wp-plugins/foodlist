<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuSectionView;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_section';
    }
    
    public function apply($attrs, $content = null)
    {
        $curmenu = Manager::getInstance()->get('cursectionview');
        $result = sprintf('[flmenu_section id="%d" instance="%d"]', $curmenu->getId(), $curmenu->getInstanceId());
        
        return $result;
    }
    
}