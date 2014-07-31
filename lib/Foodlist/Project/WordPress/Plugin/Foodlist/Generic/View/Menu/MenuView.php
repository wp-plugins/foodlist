<?php
/**
 * @version   $Id: MenuTag.php 340 2013-09-08 10:49:31Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuPostFactory;
use Artprima\WordPress\Helper\Settings as SettingsHelper;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuExcerptShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuIdShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuPermalinkShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionsShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuTitleShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuTitleTextShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuView extends BaseMenuView
{
    protected static $sm;

    protected static function applyShortcodes($content, $args)
    {
        if (self::$sm === null) {
            $sm = new ShortcodeManager();
            $sm->registerShortcode(new MenuIdShortcode());
            $sm->registerShortcode(new MenuPermalinkShortcode());
            $sm->registerShortcode(new MenuTitleShortcode($args));
            $sm->registerShortcode(new MenuTitleTextShortcode());
            $sm->registerShortcode(new MenuExcerptShortcode());
            $sm->registerShortcode(new MenuSectionsShortcode());
            $sm->registerShortcode(new MenuSectionShortcode());
            self::$sm = $sm;
            do_action('foodlist_register_menu_shortcode', self::$sm);
        }

        return self::$sm->applyShortcodes($content);
    }

    public function getHtml($args = array())
    {
        $result = '';
        
        $menu = MenuPostFactory::getOne($this->getId());

        if ($menu) {
            $helper = SettingsHelper::getInstance('foodlist');
            $result = $helper->get('template_menu');
            $result = apply_filters('foodlist_menu_template', $result, $menu);
            Manager::getInstance()->set('curmenu', $menu);
            return do_shortcode(self::applyShortcodes($result, $args));
        }
        
        return $result;
    }
    
}
