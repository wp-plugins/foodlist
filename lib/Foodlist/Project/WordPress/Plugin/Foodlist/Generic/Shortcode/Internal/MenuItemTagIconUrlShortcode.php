<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemTagIconUrlShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item_tag_icon_url';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('curitemtag');
        $url = get_foodlist_menu_tag_meta($cur->term_id, 'icon', true);
        return esc_attr($url);
    }
    
}