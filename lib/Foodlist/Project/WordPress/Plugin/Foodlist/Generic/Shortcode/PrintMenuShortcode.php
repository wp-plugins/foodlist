<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode;

use Artprima\WordPress\API\Wrapper\Generic\Shortcode;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu\MenuView;

class PrintMenuShortcode extends Shortcode
{
    protected $tag = 'flmenuprint';

    public function __construct()
    {
    }
    
    public function handle($attrs)
    {
        $link = '';
        //$this->controller->handleShortcode($attrs);
        
        $attrs = wp_parse_args($attrs, array(
            'id' => '',
        ));
        
        if ($attrs['id']) {
            $view = new MenuView();
            $view->setId($attrs['id']);
            $query = new \WP_Query(array(
                'p' => $attrs['id'],
                'post_type' => 'fl-menu',
                'post_status' => array('publish'),
                'posts_per_page' => 1,
            ));
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $url = get_permalink(get_the_ID());
                    if (strpos($url, '?') === false) {
                        $url .= '?flprint=1';
                    } else {
                        $url .= '&flprint=1';
                    }
                    $link = sprintf(__('<a href="%1$s" target="_blank"><span>Print %2$s</span></a>', 'foodlist'), esc_url($url), get_the_title());
                }
            }
            wp_reset_postdata();
        }
        
        return $link;
    }

}