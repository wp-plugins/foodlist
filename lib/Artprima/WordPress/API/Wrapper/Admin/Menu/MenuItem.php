<?php

namespace Artprima\WordPress\API\Wrapper\Admin\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItemInterface;

class MenuItem implements MenuItemInterface
{
    
    protected $pageTitle;
    protected $menuTitle;
    protected $capability = 'activate_plugins';
    protected $menuSlug;
    protected $function;
    protected $iconUrl;
    protected $position;
    protected $parent;
    
    /**
     * Page hook suffix
     * 
     * @var string 
     */
    private $pageHookSuffix;
    
    /**
     * Get page title
     * 
     * The text to be displayed in the title tags of the page when the menu is selected 
     * 
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }
    
    /**
     * Get menu title
     * 
     * The on-screen name text for the menu 
     * 
     * @return string
     */
    public function getMenuTitle()
    {
        return $this->menuTitle;
    }
    
    /**
     * Get the capability required for this menu to be displayed to the user
     * 
     * The capability required for this menu to be displayed to the user. User levels are deprecated and should not be used here! 
     * 
     * @return string
     */
    public function getCapability()
    {
        return $this->capability;
    }
    
    /**
     * Get menu slug
     * 
     * The slug name to refer to this menu by (should be unique for this menu).
     * 
     * @return string
     */
    public function getMenuSlug()
    {
        return $this->menuSlug;
    }
    
    /**
     * Get the function that displays the page content for the menu page.
     * 
     * @return array|string
     */
    public function getFunction()
    {
        if ($this->function === null) {
            return array($this, 'printContent');
        }
        return $this->function;
    }

    /**
     * Prints page html content
     * 
     * @return void
     */
    public function printContent()
    {
        ;
    }
    
    //only for menu page
    /**
     * Get the url to the icon to be used for this menu.
     * 
     * Icons should be fairly small, around 16 x 16 pixels for best results.
     * You can use the plugin_dir_url( __FILE__ ) function to get the URL
     * of your plugin directory and then add the image filename to it.
     * You can set $icon_url to "div" to have wordpress generate br tag instead of img.
     * This can be used for more advanced formating via CSS, such as changing icon on hover. 
     * 
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }
    
    /**
     * Get the position in the menu order this menu should appear.
     * 
     * The higher the number, the lower its position in the menu.
     * WARNING: if two menu items use the same position attribute, one of the items may be overwritten
     * so that only one item displays! Risk of conflict can be reduced by using decimal instead of integer
     * values, e.g. 63.3 instead of 63 (Note: Use quotes in code, IE '63.3'). 
     * 
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * 
     * Get menu sub page object
     * 
     * only for menu sub page
     * 
     * @return MenuItemInterface
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set page title
     * 
     * The text to be displayed in the title tags of the page when the menu is selected 
     * 
     * @param string $pageTitle page title
     * 
     * @return MenuItemInterace
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    /**
     * Set menu title
     * 
     * The on-screen name text for the menu 
     * 
     * @param string $menuTitle menu title
     * 
     * @return MenuItemInterace
     */
    public function setMenuTitle($menuTitle)
    {
        $this->menuTitle = $menuTitle;
        return $this;
    }
 
    /**
     * Set the capability required for this menu to be displayed to the user
     * 
     * The capability required for this menu to be displayed to the user. User levels are deprecated and should not be used here!
     * 
     * @param string $capability capability
     * 
     * @return MenuItemInterace
     */
    public function setCapability($capability)
    {
        $this->capability = $capability;
        return $this;
    }

    /**
     * Set menu slug
     * 
     * The slug name to refer to this menu by (should be unique for this menu).
     * 
     * @param string $menuSlug menu slug
     * 
     * @return MenuItemInterace
     */
    public function setMenuSlug($menuSlug)
    {
        $this->menuSlug = $menuSlug;
        return $this;
    }
    
    /**
     * Set the function that displays the page content for the menu page.
     * 
     * @param string $function function
     * 
     * @return MenuItemInterace
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }
    
    //only for menu page
    /**
     * Set menu slug
     * 
     * The slug name to refer to this menu by (should be unique for this menu).
     * 
     * @param string $iconUrl icon url
     * 
     * @return MenuItemInterace
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }
 
    /**
     * Set the position in the menu order this menu should appear.
     * 
     * The higher the number, the lower its position in the menu.
     * WARNING: if two menu items use the same position attribute, one of the items may be overwritten
     * so that only one item displays! Risk of conflict can be reduced by using decimal instead of integer
     * values, e.g. 63.3 instead of 63 (Note: Use quotes in code, IE '63.3'). 
     * 
     * @param string $position position
     * 
     * @return MenuItemInterace
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * 
     * Set menu sub page object
     * 
     * only for menu sub page
     * 
     * @param MenuItemInterface $parent
     * 
     * @return MenuItemInterface
     */
    public function setParent(MenuItemInterface $parent)
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    /**
     * Creates menu using WordPress API
     * 
     * If menu item has parent creates a subpage, otherwise creates a top level page
     * 
     * @uses add_submenu_page()
     * @uses add_menu_page()
     * 
     * @return string|bool The resulting page's hook_suffix, or false if the user does not have the capability required.
     */
    public function get()
    {
        if ($this->pageHookSuffix === null) {
            if ($this->getParent()) {
                $result = add_submenu_page(
                    $this->getParent()->getMenuSlug(),
                    $this->getPageTitle(),
                    $this->getMenuTitle(),
                    $this->getCapability(),
                    $this->getMenuSlug(),
                    $this->getFunction()
                );
            } else {
                $result = add_menu_page(
                    $this->getPageTitle(),
                    $this->getMenuTitle(),
                    $this->getCapability(),
                    $this->getMenuSlug(),
                    $this->getFunction(),
                    $this->getIconUrl(),
                    $this->getPosition()
                );
            }
            $this->pageHookSuffix = $result;
        }
        
        return $this->pageHookSuffix;
    }
    
}