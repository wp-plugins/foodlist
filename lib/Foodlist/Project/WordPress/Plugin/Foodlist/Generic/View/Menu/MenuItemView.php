<?php
/**
 * @version   $Id: MenuTag.php 340 2013-09-08 10:49:31Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItemPostFactory;

use Artprima\WordPress\Helper\Settings as SettingsHelper;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\CurrencySignShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemExcerptShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemIdShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemInstanceShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemPermalinkShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemPriceShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemTagDescriptionShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemTagIconUrlShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemTagsShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemThumbnailShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemThumbnailUrlShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal\MenuItemTitleShortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemView extends BaseMenuView
{

    protected static $sm;

    protected static function applyShortcodes($content)
    {
        if (self::$sm === null) {
            $sm = new ShortcodeManager();
            $sm->registerShortcode(new MenuItemIdShortcode());
            $sm->registerShortcode(new MenuItemPermalinkShortcode());
            $sm->registerShortcode(new MenuItemInstanceShortcode());
            $sm->registerShortcode(new MenuItemTitleShortcode());
            $sm->registerShortcode(new MenuItemExcerptShortcode());
            $sm->registerShortcode(new CurrencySignShortcode());
            $sm->registerShortcode(new MenuItemPriceShortcode());
            $sm->registerShortcode(new MenuItemThumbnailShortcode());
            $sm->registerShortcode(new MenuItemThumbnailUrlShortcode());

            $sm->registerShortcode(new MenuItemTagsShortcode());
            $sm->registerShortcode(new MenuItemTagIconUrlShortcode());
            $sm->registerShortcode(new MenuItemTagDescriptionShortcode());

            self::$sm = $sm;
            do_action('foodlist_register_menuitem_shortcode', self::$sm);
        }
        return self::$sm->applyShortcodes($content);
    }
    
    public function getTagsHtml($tags)
    {
        $result = '';
        foreach ($tags as $tag) {
            $url = get_foodlist_menu_tag_meta($tag->term_id, 'icon', true);
            $result .= '<img src="'.esc_url($url).'" alt="" />';
        }
        
        return $result;
    }
    
    public function getHtml()
    {
        $menuItem = MenuItemPostFactory::getOne($this->getId());
        
        if ($menuItem) {
            $menuItem->setInstanceId($this->getInstanceId());
            $helper = SettingsHelper::getInstance('foodlist');
            $result = $helper->get('template_menu_item');
            $result = apply_filters('foodlist_menu_item_template', $result, $menuItem);
            Manager::getInstance()->set('curitem', $menuItem);
            return do_shortcode(self::applyShortcodes($result));
        }
    }
    
}