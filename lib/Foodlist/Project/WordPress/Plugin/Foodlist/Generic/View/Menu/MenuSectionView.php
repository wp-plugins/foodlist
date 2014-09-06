<?php
/**
 * @version   $Id: MenuTag.php 340 2013-09-08 10:49:31Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuSectionPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuSectionPostFactory;
use Artprima\WordPress\Helper\Settings as SettingsHelper;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemsShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionExcerptShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionIdShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionInstanceShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionPermalinkShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuSectionTitleShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionView extends BaseMenuView
{

    protected static $sm;

    protected static function applyShortcodes($content)
    {
        if (self::$sm === null) {
            $sm = new ShortcodeManager();
            $sm->registerShortcode(new MenuSectionIdShortcode());
            $sm->registerShortcode(new MenuSectionPermalinkShortcode());
            $sm->registerShortcode(new MenuSectionInstanceShortcode());
            $sm->registerShortcode(new MenuSectionTitleShortcode());
            $sm->registerShortcode(new MenuSectionExcerptShortcode());
            $sm->registerShortcode(new MenuItemsShortcode());
            $sm->registerShortcode(new MenuItemShortcode());
            self::$sm = $sm;
            do_action('foodlist_register_menusection_shortcode', self::$sm);
        }
        return self::$sm->applyShortcodes($content);
    }

    public function getHtml()
    {
        /** @var MenuSectionPost $menuSection */
        $menuSection = MenuSectionPostFactory::getOne($this->getId());
        
        if ($menuSection) {
            $menuSection->setInstanceId($this->getInstanceId());
            $helper = SettingsHelper::getInstance('foodlist');
            $result = $helper->get('template_menu_section');
            $result = apply_filters('foodlist_menu_section_template', $result, $menuSection);
            Manager::getInstance()->set('cursection', $menuSection);
            return do_shortcode(self::applyShortcodes($result));
        }
    }
    
}
