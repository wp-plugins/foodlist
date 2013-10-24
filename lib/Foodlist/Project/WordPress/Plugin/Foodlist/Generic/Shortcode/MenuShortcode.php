<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode;

use Artprima\WordPress\API\Wrapper\Generic\Shortcode;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuView;

class MenuShortcode extends Shortcode
{
    protected $tag = 'flmenu';
    
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
            'notitle' => '',
            'noprint' => '',
            'nopdf' => '',
        ));
        
        $result = '';
        if ($attrs['id']) {
            $view = new MenuView();
            $view->setId($attrs['id']);
            $result = $view->getHtml(array(
                'notitle' => $attrs['notitle'],
            ));
            if (!$attrs['noprint'] && empty($_GET['flprint'])) {
                $result .= do_shortcode('[flmenuprint id='.$attrs['id'].']');
            }
            //if (function_exists('mpdf_pdfbutton') && !$attrs['nopdf'] && empty($_GET['flprint'])) {
            //    mpdf_pdfbutton(true, __('Download this menu as PDF'), 'my login text');
            //}
        }
        
        return $result;
    }
    
}