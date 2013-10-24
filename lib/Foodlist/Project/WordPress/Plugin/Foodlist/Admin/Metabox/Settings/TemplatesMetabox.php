<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings;

use Artprima\WordPress\API\Wrapper\Admin\Metabox\Metabox;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

class TemplatesMetabox extends Metabox
{
    
    public function __construct()
    {
        add_action('foodlist_save_settings', array($this, 'onSaveSettings'), 10, 2);
        add_action('foodlist_settings_metaboxes', array($this, 'onMetaboxes'));
    }
    
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-templates-box';
        }
        return $this->id;
    }
    
    public function getTitle()
    {
        if ($this->title === null) {
            return __('Templates', 'foodlist');
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
                    <tr valign="top">
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>'.__('Menu Template', 'foodlist').'</span></legend>
                                <label for="fl-menu-template">Menu</label>
                                <br/>
                                <div class="settings-textarea-wrapper">
                                    <textarea id="fls-template-menu" class="fl-wide" name="foodlist[template_menu]">'.esc_html($helper->get('template_menu')).'</textarea>
                                    <pre class="fl-code" data-textarea="#fls-template-menu"></pre>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>'.__('Menu Section Template', 'foodlist').'</span></legend>
                                <label for="fl-menu-section-template">Menu Section</label>
                                <br/>
                                <div class="settings-textarea-wrapper">
                                    <textarea id="fls-template-menu-section" class="fl-wide" name="foodlist[template_menu_section]">'.esc_html($helper->get('template_menu_section')).'</textarea>
                                    <pre class="fl-code" data-textarea="#fls-template-menu-section"></pre>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>'.__('Menu Item Template', 'foodlist').'</span></legend>
                                <label for="fl-menu-item-template">Menu Item</label>
                                <br/>
                                <div class="settings-textarea-wrapper">
                                    <textarea id="fls-template-menu-item" class="fl-wide" name="foodlist[template_menu_item]">'.esc_html($helper->get('template_menu_item')).'</textarea>
                                    <pre class="fl-code" data-textarea="#fls-template-menu-item"></pre>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>
        ';

        echo $result;
    }
    
    public function onSaveSettings($helper, $data)
    {
        $helper->set('template_menu', stripslashes_deep($data->get('template_menu')));
        $helper->set('template_menu_section', stripslashes_deep($data->get('template_menu_section')));
        $helper->set('template_menu_item', stripslashes_deep($data->get('template_menu_item')));
        $helper->save();
    }
    
    public function onMetaboxes($menuItem)
    {
        $this->setScreen($menuItem->get());
        $this->init();
    }
    
}
