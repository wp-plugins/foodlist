<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Page;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItemInterface;

class SettingsAdminPage extends BaseAdminPage
{

    private $messages = array();
    
    private $menuItem;
    
    public function __construct(MenuItemInterface $menuItem)
    {
        $this->menuItem = $menuItem;
        $this->messages['success'] = __('Settings saved.', 'foodlist');
        $this->messages['error'] = __('Settings not saved.', 'foodlist');
    }
    
    private function getMessage($id)
    {
        if (isset($this->messages[$id])) {
            return $this->messages[$id];
        }
        return null;
    }
    
    public function getHeading()
    {
        $header = __('Foodlist Settings', 'foodlist');
        
        return $header;
    }

    private function getMetaboxesContent($prio, $data=null)
    {
        ob_start();
        do_meta_boxes($this->menuItem->get(), $prio, $data);
        $result = ob_get_clean();
        return $result;
    }

    public function getContent()
    {
        $result = '
            <div class="wrap fl-settings">
                '.$this->getTitle().'
                '.$this->getPageData().'
            </div>
        ';

        return $result;
    }
    
    public function getPageData()
    {
        if (!current_user_can('manage_options')) {
            return __('Please contact your admin to change the settings', 'foodlist');
        }
        $content = '';
        if (isset($_GET['message']) && ($message = $this->getMessage($_GET['message']))) {
            $content .= '<div class="updated" id="message"><p>'.$message.'</p></div>';
        }

        //setup hooks
        do_action('foodlist_settings_metaboxes', $this->menuItem);
        //create metaboxes
        do_action('add_meta_boxes', $this->menuItem->get());
        
        $content .= '
            <form action="'.admin_url('admin.php?page=foodlist-settings').'" method="post">
                '.wp_nonce_field('fl_settings','foodlist[nonce]', true, false).'
                '.wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false, false).'
                '.wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false, false).'
                
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder">
                        <div id="post-body-content">
                            '.$this->getMetaboxesContent('normal').'
                            '.$this->getMetaboxesContent('additional').'
    					</div>
                    </div>
                </div>

                '.get_submit_button(__('Update', 'foodlist'), 'primary', 'submit', true).'
            </form>

        ';
        
        return $content;
    }
    
}