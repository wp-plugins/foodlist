<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode;

use Artprima\WordPress\API\Wrapper\Generic\Shortcode;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuItemView as MenuItemView;

class MenuItemShortcode extends Shortcode
{
    
    protected $tag = 'flmenu_item';
    
    //protected $controller;
    
    public function __construct(/*MenuShortcode $controller*/)
    {
        //$this->controller = $controller;
    }
    
    public function handle($attrs)
    {
        //$this->controller->handleShortcode($attrs);
        
        $attrs = wp_parse_args($attrs, array(
            'id' => '',
            'instance' => '',
        ));
        $result = '';
        if ($attrs['id']) {
            $view = new MenuItemView();
            $view->setId($attrs['id']);
            $view->setInstanceId($attrs['instance']);
            $result = $view->getHtml();
        }
        
        return $result;
    }
    
}