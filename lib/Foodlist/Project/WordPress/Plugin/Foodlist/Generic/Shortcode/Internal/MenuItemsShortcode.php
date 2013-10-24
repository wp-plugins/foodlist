<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuItemView;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemsShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_items';
    }
    
    private function parseInstanceStr($instance)
    {
        $instance = str_replace('widget-post-', '', $instance);
        list($postId, $instanceId) = explode('-', $instance);
        return array((int)$postId, (int)$instanceId);
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('cursection');

        $postData = $cur->getPostData();
        $id = $postData['id'];
        
        $order = get_post_meta($id, '_fl_menu_items_order', true);
        $items = explode(',', $order);
        
        $result = '';
        if (!empty($items) && is_array($items)) {
            foreach ($items as $instance) {
                list($postId, $instanceId) = $this->parseInstanceStr($instance);
                $item = new MenuItemView();
                $item->setId($postId);
                $item->setInstanceId($instanceId);
                //$result .= '<li>'.$section->getHtml().'</li>';
                Manager::getInstance()->set('curitemview', $item);
                $result .= $this->applyAllDeeper($content);
            }
        }
        
        
        return $result;
    }
    
}