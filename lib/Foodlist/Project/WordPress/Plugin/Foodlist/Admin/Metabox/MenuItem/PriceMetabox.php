<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\MenuItem;

use Artprima\WordPress\API\Wrapper\Admin\Metabox\PostMetabox;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItemPost;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

class PriceMetabox extends PostMetabox
{
    
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-price-box';
        }
        return $this->id;
    }
    
    public function getTitle()
    {
        if ($this->title === null) {
            return __('Price', 'foodlist');
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
        wp_nonce_field('price_meta_box_nonce', 'fl_menu_item[price][nonce]');

        $helper = SettingsHelper::getInstance('foodlist');
        
        $menuItem = new MenuItemPost($post->ID);
        $price = $menuItem->getPrice();
        
        echo '
            <label for="fl-menu-item-price-default">Price</label>
        ';
        
        if ($helper->get('show_currency_sign') && ($helper->get('currency_sign_pos') == 'before')) {
            echo '
                <span class="fl-currency-sign">'.esc_html($helper->get('currency_sign')).'</span>
            ';
        }
        
        echo '
            <input id="fl-menu-item-price-default" name="fl_menu_item[price][default]" value="'.esc_attr($price).'" type="text" />
        ';
        
        if ($helper->get('show_currency_sign') && ($helper->get('currency_sign_pos') == 'after')) {
            echo '
                <span class="fl-currency-sign">'.esc_html($helper->get('currency_sign')).'</span>
            ';
        }
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
    
        if (empty($_POST['fl_menu_item']) || !is_array($_POST['fl_menu_item'])) {
            return;
        }
        
        if (empty($_POST['fl_menu_item']['price'])  || !is_array($_POST['fl_menu_item']['price'])) {
            return;
        }
        
        $priceData = $_POST['fl_menu_item']['price'];
        
        if (!isset($priceData['nonce']) || !wp_verify_nonce($priceData['nonce'], 'price_meta_box_nonce')) {
            return; 
        }

        if (!current_user_can('edit_post')) {
            return;
        }
        
        if (isset($priceData['default'])) {
            $menuItem = new MenuItemPost($postId);
            $menuItem->setPrice($priceData['default']);
        }
    }
}
