<?php

/*
 * This file is part of the ${ProjectName} package.
 *
 * (c) ${ProjectAuthor} <${ProjectAuthorEmail}>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\MenuSection;
use Artprima\WordPress\API\Wrapper\Admin\Metabox\PostMetabox;

/**
 * Class UserNoteMetabox
 *
 * @author ${ProjectAuthor} <${ProjectAuthorEmail}>
 *
 * @package Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\MenuSection
 */
class UserNoteMetabox extends PostMetabox
{
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-user-note-box';
        }
        return $this->id;
    }

    public function getTitle()
    {
        if ($this->title === null) {
            return __('User Note', 'foodlist');
        }
        return $this->title;
    }

    public function getScreen()
    {
        if ($this->screen === null) {
            return 'fl-menu-section';
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
        wp_nonce_field('save-section-note', 'fl_menu_section[note_nonce]');

        $note = get_post_meta($post->ID, '_fl_menu_section_note', true);

        $control = '
            <input type="text" autocomplete="off" spellcheck="true" id="fl-user-note" value="'.esc_attr($note).'" size="30" name="fl_menu_section[note]">
            <p>'.__('You can type here your note about this item (can be used to distinguish different sections with the same name).', 'foodlist').'</p>
        ';

        echo $control;
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

        if (empty($_POST['fl_menu_section']) || !is_array($_POST['fl_menu_section'])) {
            return;
        }

        if (empty($_POST['fl_menu_section']['note_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['fl_menu_section']['note_nonce'], 'save-section-note')) {
            return;
        }

        if (!@current_user_can('edit_post')) {
            return;
        }

        if (!isset($_POST['fl_menu_section']['note'])  || !is_string($_POST['fl_menu_section']['note'])) {
            $noteText = '';
        } else {
            $noteText = $_POST['fl_menu_section']['note'];
        }


        global $post;
        if ($noteText) {
            update_post_meta($post->ID, '_fl_menu_section_note', $noteText);
        } else {
            delete_post_meta($post->ID, '_fl_menu_section_note');
        }
    }

}