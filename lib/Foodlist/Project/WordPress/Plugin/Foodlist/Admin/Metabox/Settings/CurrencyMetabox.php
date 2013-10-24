<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings;

use Artprima\WordPress\API\Wrapper\Admin\Metabox\Metabox;
//use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItem;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

class CurrencyMetabox extends Metabox
{
    
    public function __construct()
    {
        add_action('foodlist_save_settings', array($this, 'onSaveSettings'), 10, 2);
        add_action('foodlist_settings_metaboxes', array($this, 'onMetaboxes'));
    }
    
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-currency-box';
        }
        return $this->id;
    }
    
    public function getTitle()
    {
        if ($this->title === null) {
            return __('Currency', 'foodlist');
        }
        return $this->title;
    }
    
    public function getContext()
    {
        if ($this->context === null) {
            return 'normal';
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
        $helper = SettingsHelper::getInstance('foodlist');
        $result = '
            <table class="form-table">
                <tbody>
                    <!--
                    <tr valign="top">
                        <th scope="row">'.__('Show Currency Sign', 'foodlist').'</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>'.__('Currency Sign Visibility', 'foodlist').'</span></legend>
                                <label for="fl-show-currency-sign">
                                    <input type="checkbox" '.checked(true, $helper->get('show_currency_sign', '0') == '1', false).' value="1" id="fl-show-currency-sign" name="foodlist[show_currency_sign]" />
                                    '.__('Show', 'foodlist').'
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    -->
                    <tr valign="top">
                        <th scope="row">
                            <label for="fl-currency">'.__('Currency Sign', 'foodlist').'</label>
                        </th>
                        <td>
                            <input type="text" class="small-text" value="'.esc_attr($helper->get('currency_sign', '')).'" id="fl-currency" name="foodlist[currency_sign]" />
                        </td>
                    </tr>
                    <!--
                    <tr valign="top">
                        <th scope="row">'.__('Currency Sign Position', 'foodlist').'</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>'.__('Currency Sign Position', 'foodlist').'</span></legend>
                                <label title="after">
                                    <input type="radio" '.checked(true, $helper->get('currency_sign_pos', 'after') == 'after', false).' value="after" name="foodlist[currency_sign_pos]" /> <span>'.__('After the number', 'foodlist').'</span>
                                </label>
                                <br/>
                                <label title="before">
                                    <input type="radio" '.checked(true, $helper->get('currency_sign_pos', 'after') == 'before', false).' value="before" name="foodlist[currency_sign_pos]" /> <span>'.__('Before the number', 'foodlist').'</span>
                                </label>
                            </fieldset>
                        </td>
                    </tr> -->
                </tbody>
            </table>
        ';
        
        echo $result;
    }
    
    public function onSaveSettings($helper, $data)
    {
        $helper->set('currency_sign', $data->get('currency_sign'));
        //$helper->set('currency_sign_pos', $data->get('currency_sign_pos'));
        //$helper->set('show_currency_sign', $data->get('show_currency_sign', '0'));
        $helper->save();
    }
    
    public function onMetaboxes($menuItem)
    {
        $this->setScreen($menuItem->get());
        $this->init();
    }
    
}
