<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuSectionView;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuSectionsShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_sections';
    }
    
    private function parseInstanceStr($instance)
    {
        $instance = str_replace('widget-post-', '', $instance);
        list($postId, $instanceId) = explode('-', $instance);
        return array((int)$postId, (int)$instanceId);
    }
    
    public function apply($attrs, $content = null)
    {
        $curmenu = Manager::getInstance()->get('curmenu');

        $postData = $curmenu->getPostData();
        $id = $postData['id'];
        
        $order = get_post_meta($id, '_fl_menu_sections_order', true);
        $items = explode(',', $order);
        
        $result = '';
        if (!empty($items) && is_array($items)) {
            foreach ($items as $instance) {
                list($postId, $instanceId) = $this->parseInstanceStr($instance);
                $section = new MenuSectionView();
                $section->setId($postId);
                $section->setInstanceId($instanceId);
                //$result .= '<li>'.$section->getHtml().'</li>';
                Manager::getInstance()->set('cursectionview', $section);
                $result .= $this->applyAllDeeper($content);
            }
        }
        
        
        return $result;
    }
    
}