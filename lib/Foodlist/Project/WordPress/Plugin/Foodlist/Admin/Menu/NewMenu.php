<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItem;

class NewMenu extends MenuItem
{
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getPageTitle()
    {
        if ($this->pageTitle === null) {
            return __('Menu Editor', 'foodlist');
        }
        return $this->pageTitle;
    }
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getMenuTitle()
    {
        if ($this->menuTitle === null) {
            return __('Create New Menu', 'foodlist');
        }
        return $this->menuTitle;
    }
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getMenuSlug()
    {
        if ($this->menuSlug === null) {
            return 'foodlist-new-menu';
        }
        return $this->menuSlug;
    }

    /**
     * 
     */
    public function printContent()
    {
        echo '
            <div class="wrap">
                '.get_screen_icon().'
                <h2>'.__('Foodlist Menu Editor', 'foodlist').'</h2>
                <p>
                    <em>todo</em>
                </p>
            </div>
        ';
    }
    
}