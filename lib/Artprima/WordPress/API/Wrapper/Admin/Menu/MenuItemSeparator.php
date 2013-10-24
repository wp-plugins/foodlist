<?php

namespace Artprima\WordPress\API\Wrapper\Admin\Menu;

class MenuItemSeparator extends MenuItem
{
    
    function get() {
        global $menu;
        
        $position = $this->getPosition();
        
        $index = 0;
        foreach($menu as $offset => $section) {
            if (substr($section[2], 0, 9) == 'separator') {
                $index++;
            }
            if ($offset >= $position) {
                $menu[$position] = array('','read', 'separator'.$index, '', 'wp-menu-separator');
                break;
            }
        }
        ksort($menu);
        
        return 'separator'.$index;
    }
    
}