<?php

namespace Artprima\WordPress\API\Wrapper\Admin\Menu;

class MenuManager
{
    /**
     * Array of MenuItemInterface instances
     * 
     * @var array
     */
    private $menuItems = array();
    
    /**
     * Array of menu item hooks
     * 
     * @var array 
     */
    private $menuItemHooks = array();
    
    public function __construct()
    {
        ;
    }
    
    /**
     * Adds menu item to the registration queue
     * 
     * @param \Artprima\WordPress\API\Wrapper\AdminMenu\MenuItemInterface $menuItem
     * @return \Artprima\WordPress\API\Wrapper\AdminMenu\MenuManager
     */
    public function addMenuItem(MenuItemInterface $menuItem)
    {
        $this->menuItems[] = $menuItem;
        
        return $this;
    }
    
    /**
     * Deletes menu item from the queue by the given slug
     * 
     * @param string $menuSlug
     * @return \Artprima\WordPress\API\Wrapper\AdminMenu\MenuManager
     */
    /*
    public function deleteMenuItem($menuSlug)
    {
        if (isset($this->menuItems[$menuSlug])) {
            unset($this->menuItems[$menuSlug]);
        }
        
        return $this;
    }
    */
    
    /**
     * Sets up wordpress admin_menu hook
     * 
     * @return \Artprima\WordPress\API\Wrapper\AdminMenu\MenuManager
     */
    public function setupAdminMenuHook()
    {
        add_action('admin_menu', array($this, 'onAdminMenu'));
        return $this;
    }
    
    /**
     * WordPress admin_menu hook callback
     * 
     * @usage private
     */
    public function onAdminMenu()
    {
        foreach ($this->menuItems as $menuItem)
        {
            /* @var $menuItem \Artprima\WordPress\API\Wrapper\AdminMenu\MenuItemInterface */
            $this->menuItemHooks[$menuItem->getMenuSlug()] = $menuItem->get();
        }
    }
    
    /**
     * Get menu item hooks
     * 
     * @return array menu item hooks
     */
    public function getMenuItemHooks()
    {
        return $this->menuItemHooks;
    }
}