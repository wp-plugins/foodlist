<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItem;

class Dashboard extends MenuItem
{
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getPageTitle()
    {
        if ($this->pageTitle === null) {
            return __('Dashboard', 'foodlist');
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
            return __('Dashboard', 'foodlist');
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
            return 'foodlist-dashboard';
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
                <h2>'.__('Foodlist Dashboard', 'foodlist').'</h2>
                <div id="welcome-panel" class="welcome-panel">
                    <div class="welcome-panel-content">
                        <h3>'.__('Foodlist Plugin welcomes you!', 'foodlist').'</h3>
                        <p class="about-description">'.__("We’ve assembled some links to get you started:", 'foodlist').'</p>
                        <div class="welcome-panel-column-container">
                            <div class="welcome-panel-column">
                                <div class="welcome-panel-column">
                                    <h4>'.__('Get started', 'foodlist').'</h4>
                                    <a href="'.admin_url('post-new.php?post_type=fl-menu-item').'" class="button button-primary button-hero">'.__('Create New Menu Item', 'foodlist').'</a>
                                    <p>'.__('-- or --', 'foodlist').'</p>
                                    <p>
                                        <a href="'.admin_url('post-new.php?post_type=fl-menu-section').'" class="">'.__('Create New Menu Section', 'foodlist').'</a>
                                        <br/>
                                        <a href="'.admin_url('post-new.php?post_type=fl-menu').'" class="">'.__('Create New Menu', 'foodlist').'</a>
                                    </p>
                                </div>
                            </div>
                            <div class="welcome-panel-column">
                                <h4>'.__('Next Steps', 'foodlist').'</h4>
                                <ul>
                                    <li>
                                        <a href="'.admin_url('admin.php?page=foodlist-section-manager').'" class="welcome-icon">'.__('Build Sections', 'foodlist').'</a>
                                    </li>
                                    <li>
                                        <a href="'.admin_url('admin.php?page=foodlist-menu-manager').'" class="welcome-icon">'.__('Build Menus', 'foodlist').'</a>
                                    </li>
                                    <li>
                                        <a href="'.admin_url('admin.php?page=foodlist-menu-tags').'" class="welcome-icon">'.__('Manage Menu Tags', 'foodlist').'</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="welcome-panel-column welcome-panel-last">
                                <h4>'.__('More Actions', 'foodlist').'</h4>
                                <ul>
                                    <li>
                                        <a href="'.admin_url('admin.php?page=foodlist-settings').'" class="welcome-icon">'.__('Tune Settings', 'foodlist').'</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
    
}