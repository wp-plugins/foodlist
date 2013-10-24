<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings;

use Artprima\WordPress\API\Wrapper\Admin\Metabox\Metabox;
//use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItem;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

class DataSettingsMetabox extends Metabox
{
    
    public function __construct()
    {
        add_action('foodlist_save_settings', array($this, 'onSaveSettings'), 10, 2);
        add_action('foodlist_settings_metaboxes', array($this, 'onMetaboxes'));
    }
    
    public function getId()
    {
        if ($this->id === null) {
            return 'fl-data-settings-box';
        }
        return $this->id;
    }
    
    public function getTitle()
    {
        if ($this->title === null) {
            return __('Data Settings', 'foodlist');
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
                        <th scope="row">'.__('Data state on plugin deactivate', 'foodlist').'</th>
                        <td>
                            <legend class="screen-reader-text"><span>'.__('Data state on plugin deactivate', 'foodlist').'</span></legend>
                            <label>
                                <input type="checkbox" '.checked(true, $helper->get('delete_data_on_plugin_deactivate', 'off') == 'on', false).' value="on" name="foodlist[delete_data_on_plugin_deactivate]" /> <span>'.__('Delete data on plugin deactivate', 'foodlist').'</span>
                            </label>
                        </td>
                    </tr>
        ';

        if (get_option('foodlist_demo_data_ids')) {
            $result .=
            '
                    <tr valign="top">
                        <th scope="row">'.__('Demo data', 'foodlist').'</th>
                        <td>
                            <legend class="screen-reader-text"><span>'.__('Delete demo data', 'foodlist').'</span></legend>
                            <button id="fl-delete-demo-data" class="button button-secondary">Delete demo data</button>
                        </td>
                    </tr>
            ';
        }
        $result .= '
                </tbody>
            </table>
        ';
        
        echo $result;
    }
    
    public function onSaveSettings($helper, $data)
    {
        $helper->set('delete_data_on_plugin_deactivate', $data->get('delete_data_on_plugin_deactivate'));
        $helper->save();
    }
    
    public function onMetaboxes($menuItem)
    {
        $this->setScreen($menuItem->get());
        $this->init();
    }
    
}
