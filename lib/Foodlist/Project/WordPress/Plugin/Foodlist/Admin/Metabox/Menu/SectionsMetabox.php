<?php

/*
 * This file is part of the Foodlist package.
 *
 * (c) Denis Voytyuk <ask AT artprima.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Metabox\PostMetabox;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

/**
 * Class SectionsMetabox
 *
 * @author Denis Voytyuk <ask@artprima.cz>
 *
 * @package Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\MenuItem
 */
class SectionsMetabox extends PostMetabox
{

    private function parseInstanceStr($instance)
    {
        $instance = str_replace('widget-post-', '', $instance);
        list($postId, $instanceId) = explode('-', $instance);
        return array((int)$postId, (int)$instanceId);
    }

    public function getId()
    {
        if ($this->id === null) {
            return 'fl-sections-box';
        }
        return $this->id;
    }

    public function getTitle()
    {
        if ($this->title === null) {
            return __('Sections', 'foodlist');
        }
        return $this->title;
    }

    public function getScreen()
    {
        if ($this->screen === null) {
            return 'fl-menu';
        }
        return $this->screen;
    }

    public function getContext()
    {
        if ($this->context === null) {
            return 'advanced';
        }
        return $this->context;
    }

    public function getPriority()
    {
        if ($this->priority === null) {
            return 'default';
        }
        return $this->priority;
    }

    public function content()
    {
        global $post;
        wp_nonce_field('save-menu-sections', 'fl_menu[sections_nonce]');

        $helper = SettingsHelper::getInstance('foodlist');

        $order = get_post_meta($post->ID, '_fl_menu_sections_order', true);

        if ($order) {
            $sections = explode(',', $order);
        } else {
            $sections = null;
        }

        $template = '
            <li class="widget-top fl-menu-sortable-item">
                    <div class="widget-title"><h4>%s</h4></div>
                    <div class="fl-menu-sortable-item-remove dashicons dashicons-no"></div>
                    <input type="hidden" name="fl_menu[sections][]" value="%s" />
            </li>
        ';

        echo '<ul id="fl-menu-sortable-list" class="widget ui-draggable" data-template="'.esc_attr(sprintf($template, '__title__', '__id__')).'" data-context="sections" data-nonce="fl_menu[sections_nonce]">';
        if (!empty($sections) && is_array($sections)) {
            foreach ($sections as $instance) {
                list($postId, $instanceId) = $this->parseInstanceStr($instance);
                $title = get_the_title($postId);
                echo sprintf($template, esc_html($title), (int)$postId);
            }
        }
        echo "</ul>";
        echo '
            <input id="metabox-sortable-dropdown" type="text" placeholder="'.esc_attr(__('add a new section', 'foodlist')).'" />
        ';
    }

    public function onPostSave($postId)
    {
        //if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        //    return;
        //}

        if (empty($_POST['post_type'])) {
            return;
        }

        if ( $this->getScreen() != $_POST['post_type'] ) {
            return;
        }

        if (empty($_POST['fl_menu']) || !is_array($_POST['fl_menu'])) {
            return;
        }

        if (empty($_POST['fl_menu']['sections_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['fl_menu']['sections_nonce'], 'save-menu-sections')) {
            return;
        }

        if (!current_user_can('edit_post')) {
            return;
        }

        if (!isset($_POST['fl_menu']['sections'])  || !is_array($_POST['fl_menu']['sections'])) {
            $sectionsData = array();
        } else {
            $sectionsData = $_POST['fl_menu']['sections'];
        }

        $sections = array();
        foreach ($sectionsData as $section) {
            $sections[] = 'widget-post-'.((int)$section).'-1';
        }

        $sectionsText = implode(',', $sections);

        global $post;
        if ($sectionsText) {
            update_post_meta($post->ID, '_fl_menu_sections_order', $sectionsText);
        } else {
            delete_post_meta($post->ID, '_fl_menu_sections_order');
        }
    }


} 