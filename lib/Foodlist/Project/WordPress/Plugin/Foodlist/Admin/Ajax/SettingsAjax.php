<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax;

class SettingsAjax extends Base
{
    protected $metaKeys = array(
        'multi_number' => '_fl_menu_item_multi_number',
        'items' => '_fl_menu_items',
        'order' => '_fl_menu_items_order',
    );
    

    public function getNonceAction()
    {
        return 'fl_settings';
    }
    
    public function ajaxDeleteDemoData()
    {
        $demoData = get_option('foodlist_demo_data_ids');
        if (!$demoData) {
            return;
        }
        
        foreach ($demoData['items'] as $item) {
            wp_delete_post($item);
        }
        foreach ($demoData['sections'] as $item) {
            wp_delete_post($item);
        }
        foreach ($demoData['menus'] as $item) {
            wp_delete_post($item);
        }
        foreach ($demoData['attachments'] as $item) {
            wp_delete_attachment($item);
        }
        foreach($demoData['tags'] as $tag) {
            wp_delete_term($tag, 'fl-menu-tag');
        }

        update_option('foodlist_demo_data_ids', 0);
        
        die('1');
    }
    
    
}