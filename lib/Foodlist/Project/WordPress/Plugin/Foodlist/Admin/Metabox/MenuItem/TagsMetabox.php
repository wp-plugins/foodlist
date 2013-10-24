<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\MenuItem;

use Artprima\WordPress\API\Wrapper\Admin\Metabox\PostMetabox;
//use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItem;
//use Artprima\WordPress\Helper\Settings as SettingsHelper;

class TagsMetabox extends PostMetabox
{
    
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-tags-box';
        }
        return $this->id;
    }
    
    public function getTitle()
    {
        if ($this->title === null) {
            return __('Tags', 'foodlist');
        }
        return $this->title;
    }
    
    public function getScreen()
    {
        if ($this->screen === null) {
            return 'fl-menu-item';
        }
        return $this->screen;
    }
    
    public function getContext()
    {
        if ($this->context === null) {
            return 'side';
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
        wp_nonce_field('tags_meta_box_nonce', 'fl_menu_item[tags][nonce]');
        
        $terms = get_terms('fl-menu-tag', array(
            'hide_empty' => false,
        ));
        
        echo '
            <!-- <label for="fl-menu-item-tags-select">'.__('Tags', 'foodlist').'</label> -->
            <select id="fl-menu-item-tags-select" name="fl_menu_item[tags][data][]" multiple="multiple">
        ';
        
        $postTerms = wp_get_post_terms($post->ID, 'fl-menu-tag', array(
            'fields' => 'slugs',
        ));

        foreach ($terms as $term) {
            $url = get_foodlist_menu_tag_meta($term->term_id, 'icon', true);
            printf('<option value="%s" '.selected(in_array($term->slug, $postTerms), true, false).' data-img-url="%s">%s</option>', esc_attr($term->slug), esc_url($url), esc_html($term->name));
        }
        echo '</select>';
        echo '<p class="description">'.__('Click on white space to add more tags', 'foodlist').'</p>';
    }
    
    public function onPostSave($postId)
    {
        //if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        //    return;
        //}
        
        if ( $this->getScreen() != $_POST['post_type'] ) {
            return;
        }
    
        if (empty($_POST['fl_menu_item']) || !is_array($_POST['fl_menu_item'])) {
            return;
        }
        
        if (empty($_POST['fl_menu_item']['tags'])  || !is_array($_POST['fl_menu_item']['tags'])) {
            return;
        }
        
        $data = $_POST['fl_menu_item']['tags'];
        
        if (!isset($data['nonce']) || !wp_verify_nonce($data['nonce'], 'tags_meta_box_nonce')) {
            return; 
        }

        if (!current_user_can('edit_post')) {
            return;
        }

        if (isset($data['data'])) {
            wp_set_post_terms($postId, $data['data'], 'fl-menu-tag');
            //var_dump($data['data']);die();
            //$menuItem = new MenuItem($postId);
            //$menuItem->setPrice($data['default']);
        }
    }
}
