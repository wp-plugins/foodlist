<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\Manager;

class Section extends Base
{
    protected $metaKeys = array(
        'multi_number' => '_fl_menu_item_multi_number',
        'items' => '_fl_menu_items',
        'order' => '_fl_menu_items_order',
    );
    

    public function getNonceAction()
    {
        return 'save-section-items';
    }

    public function ajaxGetItems()
    {
        $this->getPagedPosts('fl-menu-item');
    }
}