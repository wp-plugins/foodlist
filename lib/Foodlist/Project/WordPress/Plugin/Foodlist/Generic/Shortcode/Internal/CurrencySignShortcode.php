<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Artprima\WordPress\Helper\Settings as SettingsHelper;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class CurrencySignShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'currency_sign';
    }
    
    public function apply($attrs, $content = null)
    {
        $helper = SettingsHelper::getInstance('foodlist');
        
        return $helper->get('currency_sign');
    }
    
}