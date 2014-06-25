<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\Manager;

class Menu extends Base
{
    protected $metaKeys = array(
        'multi_number' => '_fl_menu_section_multi_number',
        'items' => '_fl_menu_sections',
        'order' => '_fl_menu_sections_order',
    );
    

    public function getNonceAction()
    {
        return 'save-menu-sections';
    }

    public function ajaxGetSections()
    {
        $this->getPagedPosts('fl-menu-section');
    }
}