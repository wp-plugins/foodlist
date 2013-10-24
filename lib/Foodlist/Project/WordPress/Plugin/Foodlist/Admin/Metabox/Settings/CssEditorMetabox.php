<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings;

use Artprima\Helper\ArrayProperties;
use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItemInterface;
use Artprima\WordPress\API\Wrapper\Admin\Metabox\Metabox;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

class CssEditorMetabox extends Metabox
{
    
    public function __construct()
    {
        add_action('foodlist_save_settings', array($this, 'onSaveSettings'), 10, 2);
        add_action('foodlist_settings_metaboxes', array($this, 'onMetaboxes'));
    }
    
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-css-editor-box';
        }
        return $this->id;
    }
    
    public function getTitle()
    {
        if ($this->title === null) {
            return __('Custom CSS Rules', 'foodlist');
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
                                <legend class="screen-reader-text"><span>'.__('Custom CSS Rules', 'foodlist').'</span></legend>
                                <label for="fl-custom-css-rules">CSS Editor</label>
                                <br/>
                                <div class="settings-textarea-wrapper">
                                    <textarea id="fls-custom-css-rules" class="fl-wide" name="foodlist[custom_css_rules]">'.esc_html($helper->get('custom_css_rules')).'</textarea>
                                    <pre class="fl-code" data-textarea="#fls-custom-css-rules" data-mode="css"></pre>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>
        ';

        echo $result;
    }

    /**
     * Called on settings save
     *
     * @param SettingsHelper $helper
     * @param ArrayProperties $data
     */
    public function onSaveSettings(SettingsHelper $helper, ArrayProperties $data)
    {
        $helper->set('custom_css_rules', stripslashes_deep($data->get('custom_css_rules')));
        $helper->save();
    }
    
    public function onMetaboxes(MenuItemInterface $menuItem)
    {
        $this->setScreen($menuItem->get());
        $this->init();
    }
    
}
