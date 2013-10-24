<?php
/**
 * @version   $Id: Frontend.php 342 2013-09-08 19:41:25Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Artprima\WordPress\Helper\Settings as SettingsHelper;

class FrontendController extends BaseController
{
    
    /**
     * Inits controller
     */
    public function init()
    {
        $this->loadStyles();

        //add_filter('single_template', array($this, 'preparePrintableMenu'));
        add_filter('the_content', array($this, 'prepareMenuPost'));
        add_action('template_redirect', array($this, 'preparePrintableMenu'));

        return $this;
    }

    public function preparePrintableMenu()
    {
        if (!is_single()) {
            return;
        }
        $object = get_queried_object();
        if (!$object) {
            return;
        }
        if (!empty($_GET['flprint']) && ($object->post_type === 'fl-menu')) {
            $menuTpl = '
                <html>
                    <head>
                        <title>%1$s</title>
                        <link
                            rel="stylesheet"
                            id="fl-printed-css"
                            href="%2$s"
                            type="text/css"
                            media="all"
                        />
                        <link
                            rel="stylesheet"
                            id="fonts-css"
                            href="//fonts.googleapis.com/css?family=Source+Sans+Pro%%3A300%%2C400%%2C700%%2C300italic%%2C400italic%%2C700italic%%7CBitter%%3A400%%2C700&#038;subset=latin%%2Clatin-ext"
                            type="text/css"
                            media="all"
                        />
                    </head>
                <body onload="javascript:window.print()">
                    <div class="fl-print-data">
                        %3$s
                    </div>
                    <div class="fl-print-copyright">
                        %4$s
                    </div>
                </body>
                </html>
            ';
            $menuTpl = apply_filters('foodlist_printable_menu_template', $menuTpl);
            echo sprintf(
                $menuTpl,
                __('Printed Menu', 'foodlist'),
                $this->getManager()->getPluginUrl().'/assets/css/printed.css',
                do_shortcode('[flmenu id="'.$object->ID.'"]'),
                __('Created and printed with Foodlist.', 'foodlist')
            );
            die();
        }
    }

    public function prepareMenuPost($content)
    {
        global $post;
        if ($post->post_type === 'fl-menu') {
            $content .= '[flmenu id="'.$post->ID.'" notitle="1"]';
        }
        return $content;
    }

    private function loadStyles()
    {
        $self = $this;
        add_action('wp_enqueue_scripts', function(/*$hookSuffix*/) use ($self) {
            wp_enqueue_style('foodlist-frontend', $self->getManager()->getPluginUrl().'/assets/css/frontend.css');
            //wp_enqueue_script('foodlist-admin', $self->getManager()->getPluginUrl().'/assets/js/admin.js', array('jquery'), false, true);
        });
        add_action('wp_head', function() {
            $helper = SettingsHelper::getInstance('foodlist');
            $rules = $helper->get('custom_css_rules');

            if ($rules) {
                echo '
                    <style>
                        '.$rules.'
                    </style>
                ';
            }
        });
    }
    
}