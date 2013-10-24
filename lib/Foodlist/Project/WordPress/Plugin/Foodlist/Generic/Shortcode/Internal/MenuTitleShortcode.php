<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;

class MenuTitleShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_title';
    }
    
    public function apply($attrs, $content = null)
    {
        if (!empty($this->args['notitle'])) {
            return '';
        }
        return $this->applyAllDeeper($content);
    }
    
}