<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItem;

class Settings extends MenuItem
{
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getPageTitle()
    {
        if ($this->pageTitle === null) {
            return __('Settings', 'foodlist');
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
            return __('Settings', 'foodlist');
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
            return 'foodlist-settings';
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
                <h2>'.__('Foodlist Settings', 'foodlist').'</h2>
                <p>
                    
                </p>
            </div>
        ';
    }
    
}