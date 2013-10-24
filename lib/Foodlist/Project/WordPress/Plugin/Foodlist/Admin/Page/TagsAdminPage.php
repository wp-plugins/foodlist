<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Page;

use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Table\TagListTableList as TagListTableList;

class TagsAdminPage extends BaseAdminPage
{
    
    private $messages = array();
    
    public function __construct()
    {
        $this->messages[1] = __('Item added.', 'foodlist');
        $this->messages[2] = __('Item deleted.', 'foodlist');
        $this->messages[3] = __('Item updated.', 'foodlist');
        $this->messages[4] = __('Item not added.', 'foodlist');
        $this->messages[5] = __('Item not updated.', 'foodlist');
        $this->messages[6] = __('Items deleted.', 'foodlist');
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
        $action = empty($_GET['action']) ? 'list' : $_GET['action'];
        switch ($action) {
            case 'edit':
                $header = __('Foodlist: Edit Menu Tag', 'foodlist');
                break;
            case 'add':
                $header = __('Foodlist: Add Menu Tag', 'foodlist');
                break;
            case 'list':
            default:
                $header = __('Foodlist Menu Tags', 'foodlist');
        }
        
        return $header;
    }

    private function getPageDataList()
    {
        //Create an instance of our package class...
        $tagListTable = new TagListTableList();
        //Fetch, prepare, sort, and filter our data...
        $tagListTable->prepare_items();
        
        ob_start();
        $tagListTable->display();
        $tableContent = ob_get_clean();
        
        $result = '';
        if (isset($_GET['message']) && ($message = $this->getMessage((int)$_GET['message']))) {
            $result .= '<div class="updated" id="message"><p>'.$message.'</p></div>';
        }
        
        /*
        $result .= '
            <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
                <p>This page demonstrates the use of the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt> class in plugins.</p> 
                <p>For a detailed explanation of using the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt>
                class in your own plugins, you can view this file <a href="/wp-admin/plugin-editor.php?plugin=table-test/table-test.php" style="text-decoration:none;">in the Plugin Editor</a> or simply open <tt style="color:gray;"><?php echo __FILE__ ?></tt> in the PHP editor of your choice.</p>
                <p>Additional class details are available on the <a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WordPress Codex</a>.</p>
            </div>
        ';
        */
        
        $result .= '
            <div id="col-container">
                <div id="col-right">
                    <div class="col-wrap">
                        <form id="fl-menu-tags-filter" method="get">
                            <input type="hidden" name="page" value="'.$_REQUEST['page'].'" />
                            '.$tableContent.'
                        </form>
                    </div>
                </div>
                <div id="col-left">
                    <div class="col-wrap">
                        <div class="form-wrap">
                            <h3>Add New Tag</h3>
                            <form class="validate" action="'.admin_url('admin.php?page=foodlist-menu-tags&action=addedtag').'" method="post" id="fl-edit-menu-tag">
                                '.wp_nonce_field('fl_edit_menu_tag','_wpnonce_fl_edit_menu_tag', true, false).'
                                <input type="hidden" value="fl-menu-tag" name="taxonomy" />
                                <div class="form-field form-required">
                                    <label for="tag-name">Name</label>
                                    <input type="text" aria-required="true" size="40" value="" id="tag-name" name="fl_menu_tag[name]">
                                    <p>'.__('Menu tag name how it appears on your site.', 'foodlist').'</p>
                                </div>
                                <div class="form-field">
                                    <label for="tag-slug">Slug</label>
                                    <input type="text" size="40" value="" id="tag-slug" name="fl_menu_tag[slug]">
                                    <p>'.__('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'foodlist').'</p>
                                </div>
                                <div class="form-field">
                                    <label for="tag-description">Description</label>
                                    <textarea cols="40" rows="5" id="tag-description" name="fl_menu_tag[description]"></textarea>
                                    <p>'.__('The description that is shown when you move the mouse over the tag icon.', 'foodlist').'</p>
                                </div>
                                <div class="form-field">
                                    <label>'.__('Icon', 'foodlist').'</label>
                                    '.$this->getUploadArea().'
                                </div>
                                '.get_submit_button(__('Add New Tag', 'foodlist'), 'primary', 'submit', true).'
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        ';
        
        return $result;
    }

    private function getUploadArea($url='')
    {
        $result = '<input type="hidden" name="fl_menu_tag[icon]" value="'.esc_url($url).'" size="40" aria-required="true" />';
        
        /*
        if (!_device_can_upload()) {
            $result = '<p>' . sprintf( __('The web browser on your device cannot be used to upload files. You may be able to use the <a href="%s">native app for your device</a> instead.'), 'http://wordpress.org/mobile/' ) . '</p>';
        } else {
            $result = '
                <div id="fl-uploader-container">
                    <div class="upload-dropzone">
                        '.__('Drop a file here or <a href="#" class="upload">select a file</a>.', 'foodlist').'
                    </div>
                    <div class="upload-fallback">
                        <span class="button-secondary">'.__('Select File', 'foodlist').'</span>
                    </div>
                </div>
                <div class="media-progress-bar"><div></div></div>
                <p class="description">'.__('You may enter icon url or upload a new one.', 'foodlist').'</p>
            ';
        }
        */
        
        $result .= '
            <div id="fl-image-container" '.($url ? '' : 'style="display: none"').'>
                <p class="fl-image-container">
                    <a href="#" id="set-menu-tag-icon-img"><img src="'.esc_url($url).'" alt="Menu Tag Icon" /></a>
                </p>
                <p class="fl-image-container">
                    <a href="#" id="remove-menu-tag-icon">'.__('Remove icon', 'foodlist').'</a>
                </p>
            </div>
            <div id="fl-image-uploader" '.($url ? 'style="display: none"' : '').'>
                <p class="fl-image-upload">
                    <span class="button-secondary" id="set-menu-tag-icon">'.__('Set menu tag icon', 'foodlist').'</span>
                </p>
            </div>
        ';
    
        return $result;
    }
    
    private function getPageDataEdit()
    {
        if (empty($_GET['fl-menu-tag'])) {
            return $this->getPageDataList();
        }
        
        $term = get_term($_GET['fl-menu-tag'], 'fl-menu-tag');
        if (!$term) {
            return $this->getPageDataList();
        }
        
        $pageData = '
        <form class="validate" action="'.admin_url('admin.php?page=foodlist-menu-tags&action=editedtag&fl-menu-tag='.$term->term_id).'" method="post" id="fl-edit-menu-tag" name="fl_edit_menu_tag">
            '.wp_nonce_field('fl_edit_menu_tag','_wpnonce_fl_edit_menu_tag', true, false).'
            <input type="hidden" value="'.($term->term_id).'" name="fl_menu_tag[term_id]" />
            <input type="hidden" value="fl-menu-tag" name="taxonomy" />
        ';
        
        $url = get_foodlist_menu_tag_meta($_GET['fl-menu-tag'], 'icon', true);
        
        $pageData .= '
            <table class="form-table">
                <tbody>
                    <tr class="form-field form-required">
                        <th valign="top" scope="row"><label for="name">'.__('Name', 'foodlist').'</label></th>
                        <td>
                            <input type="text" aria-required="true" size="40" value="'.esc_attr($term->name).'" id="name" name="fl_menu_tag[name]" />
                            <p class="description">'.__('Menu tag name how it appears on your site.', 'foodlist').'</p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th valign="top" scope="row"><label for="slug">'.__('Slug', 'foodlist').'</label></th>
                        <td>
                            <input type="text" size="40" value="'.esc_attr($term->slug).'" id="slug" name="fl_menu_tag[slug]" />
                            <p class="description">'.__('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'foodlist').'</p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th valign="top" scope="row"><label for="description">'.__('Description', 'foodlist').'</label></th>
                        <td>
                            <textarea class="large-text" cols="50" rows="5" id="description" name="fl_menu_tag[description]">'.esc_html($term->description).'</textarea><br/>
                            <span class="description">'.__('The description that is shown when you move the mouse over the tag icon.', 'foodlist').'</span>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th valign="top" scope="row"><label>'.__('Icon', 'foodlist').'</label></th>
                        <td>
                            '.$this->getUploadArea($url).'
                        </td>
                    </tr>
                </tbody>
            </table>
            '.get_submit_button(__('Update', 'foodlist'), 'primary', 'submit', true).'
        </form>
        ';
        
        return $pageData;
    }
    
    public function getPageData()
    {
        $action = empty($_GET['action']) ? 'list' : $_GET['action'];
        switch ($action) {
            case 'edit':
                $content = $this->getPageDataEdit();
                break;
            case 'add':
                $content = __('Foodlist: Add Menu Tag', 'foodlist');
                break;
            case 'list':
            default:
                $content = $this->getPageDataList();
        }
        
        return $content;
    }
    
}