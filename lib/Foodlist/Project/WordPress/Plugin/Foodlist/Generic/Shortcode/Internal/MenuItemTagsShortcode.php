<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuItemView;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemTagsShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item_tags';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('curitem');
        $tags = $cur->getTags();
        
        $result = '';
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                Manager::getInstance()->set('curitemtag', $tag);
                $result .= $this->applyAllDeeper($content);
            }
        }
        
        
        return $result;
    }
    
}