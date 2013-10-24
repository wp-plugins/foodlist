<?php
/**
 * @version   $Id: Admin.php 352 2013-09-23 23:26:21Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItemSeparator;
use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuManager;
use Artprima\WordPress\API\Wrapper\Admin\Metabox\MetaboxScreen;

use Artprima\WordPress\API\Wrapper\Admin\UI\AdminPointer;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu\Dashboard;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu\Settings;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu\MenuManager as MenuSectionManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu\SectionManager as SectionItemManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu\MenuTags;

use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings\CssEditorMetabox;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings\CurrencyMetabox;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings\DataSettingsMetabox;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Metabox\Settings\TemplatesMetabox;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Page\TagsAdminPage as TagsPage;
use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Page\SettingsAdminPage as SettingsPage;

use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\Page\TagPageController as TagController;
use Foodlist\Project\WordPress\Plugin\Foodlist\Controller\Page\SettingsPageController as SettingsController;

class AdminController extends BaseController
{
    /**
     * Inits controller
     */
    public function init()
    {
        $this->maybeUpgrade();
        $this->loadStyles();
        $this->initSubControllers();
        $this->setupAdminPointers();
        $this->createMetaboxes();
        $this->registerMenuPages();

        if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
            add_action('media_buttons', array($this, 'addEditorButtons'), 21);
            add_action('admin_footer',  array($this, 'addMcePopup'));
        }

        add_filter('wp_prepare_attachment_for_js', array($this, 'imageSizesJs'), 10, 3);
        return $this;
    }

    private function maybeUpgrade()
    {
        $controller = new UpgraderController($this->getManager());
        $controller->init();
    }
    
    private function initSubControllers()
    {
        if (empty($_GET['page'])) {
            return $this;
        }
        
        if  ($_GET['page'] == 'foodlist-menu-tags') {
            $controller = new TagController($this->getManager());
            $controller->init();
        }
        
        if  ($_GET['page'] == 'foodlist-settings') {
            $controller = new SettingsController($this->getManager());
            $controller->init();
        }
    }
    
    private function loadStyles()
    {
        $self = $this;
        add_action('admin_enqueue_scripts', function($hookSuffix) use ($self) {
            wp_enqueue_style('foodlist-admin', $self->getManager()->getPluginUrl().'/assets/css/admin.css');
            if (
                (strpos($hookSuffix, 'page_foodlist') !== false)
                /*
                || (
                    isset($_GET['post_type'])
                    && (
                        ($_GET['post_type'] == 'fl-menu')
                        || ($_GET['post_type'] == 'fl-menu-section')
                        || ($_GET['post_type'] == 'fl-menu-item')
                    )
                )
                 */
                || ((($screen = get_current_screen()) !== null) && ($screen->id == 'fl-menu-item'))
            ) {
                if(function_exists('wp_enqueue_media')){
                    wp_enqueue_media();
                }else{
                    wp_enqueue_style('thickbox');
                    wp_enqueue_script('media-upload');
                    wp_enqueue_script('thickbox');
                }
                //wp_enqueue_script('wp-plupload');
                wp_enqueue_script('jquery-color');
                //wp_plupload_default_settings();
                wp_enqueue_script('select2', $self->getManager()->getPluginUrl().'/assets/js/select2/select2.min.js', array('jquery'), '3.4.2', true);
                wp_enqueue_style('select2', $self->getManager()->getPluginUrl().'/assets/js/select2/select2.css', array(), '3.4.2');

                wp_enqueue_script('ace-editor', 'http://d1n0x3qji82z53.cloudfront.net/src-min-noconflict/ace.js');
                wp_enqueue_script('foodlist-admin', $self->getManager()->getPluginUrl().'/assets/js/admin.js', array('jquery', 'ace-editor'), false, true);
                
                wp_enqueue_script('foodlist-menu-manager', $self->getManager()->getPluginUrl().'/assets/js/menu-manager.js',
                    array(
                        'jquery',
                        'jquery-ui-mouse',
                        'jquery-ui-draggable',
                        'jquery-ui-droppable',
                        'jquery-ui-sortable',
                    ),
                false, true);
            }
        });
    }
    
    public function setupAdminPointers()
    {
        $ap = AdminPointer::getInstance();
        $ap->setScriptUrl($this->getManager()->getPluginUrl().'/assets/js/admin-pointers.js')
            ->add('fl-welcome', array(
                //'target' => '#change-permalinks',
                'target' => '#toplevel_page_foodlist-dashboard',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Welcome to the Foodlist plugin' ,'foodlist'),
                        __( 'With the help of this plugin you will easily create your restaurant menus.<br/><br/> Now you will be guided through the plugin.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ))->add('fl-step-one', array(
                //'target' => '#change-permalinks',
                'target' => '#menu-posts-fl-menu-item',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Step One: Create Menu Items' ,'foodlist'),
                        __( 'First of all you need to <strong>create menu items for your menu</strong>. This is as simple as creating a post or a page.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ))->add('fl-step-two', array(
                //'target' => '#change-permalinks',
                'target' => '#menu-posts-fl-menu-section',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Step Two: Create Menu Sections' ,'foodlist'),
                        __( 'After you created your first menu items, you need to <strong>create menu sections</strong>. They will allow you to categorize your menus.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ))->add('fl-step-three', array(
                //'target' => '#change-permalinks',
                'target' => '#menu-posts-fl-menu',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Step Three: Create Menus' ,'foodlist'),
                        __( 'After you created your first menu sections, you need to <strong>create menus</strong>.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ))->add('fl-step-four', array(
                //'target' => '#change-permalinks',
                'target' => '#toplevel_page_foodlist-dashboard',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Step Four: Put Items Under Sections' ,'foodlist'),
                        __( 'Now you have all the elements created, but the menus are not yet built. First of all you need to <strong>put your menu items under the chosen sections</strong>.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ))->add('fl-step-five', array(
                //'target' => '#change-permalinks',
                'target' => '#toplevel_page_foodlist-dashboard',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Step Five: Put Sections Under Menus' ,'foodlist'),
                        __( 'This is the final preparation step. You need to <strong>put your sections under the chosen menus</strong>.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ))->add('fl-step-six', array(
                //'target' => '#change-permalinks',
                'target' => '#toplevel_page_foodlist-dashboard',
                'options' => array(
                    'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                        __( 'Step Six: Go Online' ,'foodlist'),
                        __( 'Now you are almost done. To make your menus online you need to put the correspondent shortcodes (<code>[flmenu id=&quot;123&quot;]</code>) into a post or a page.','foodlist')
                    ),
                    'position' => array( 'edge' => 'left', 'align' => 'middle' )
                )
            ));
    }
    
    /**
     * @usage private
     */
    public function imageSizesJs($response, $attachment, $meta)
    {
        $size_array = array('fl-menu-tag-icon') ;

        foreach ($size_array as $size) {
            if (isset($meta['sizes'][ $size ])) {
                $attachment_url = wp_get_attachment_url($attachment->ID);
                $base_url = str_replace(wp_basename($attachment_url), '', $attachment_url);
                $size_meta = $meta['sizes'][$size];

                $response['sizes'][str_replace('-', '_', $size)] = array(
                    'height'        => $size_meta['height'],
                    'width'         => $size_meta['width'],
                    'url'           => $base_url . $size_meta['file'],
                    'orientation'   => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
                );
            }
        }

        return $response;
    }
    
    /**
     * Registeres menu pages
     * 
     * @return Manager
     */
    private function registerMenuPages()
    {
        $manager = new MenuManager();
        
        do_action('wpfl_pre_wp_menu', $manager);
        
        // separator before main menu
        $sep = new MenuItemSeparator();
        $sep->setPosition('25.12390');
        $manager->addMenuItem($sep);
        
        // main menu page
        $dashboard = new Dashboard();
        $dashboard->setMenuTitle(__('Foodlist', 'foodlist'));
        $dashboard->setPosition('25.12391');
        $dashboard->setCapability('edit_pages');
        $dashboard->setFunction(function(){});
        $manager->addMenuItem($dashboard);
        
        // separator after main menu
        //$sep = new MenuItemSeparator();
        //$sep->setPosition('25.12392');
        //$manager->addMenuItem($sep);
        
        // dashboard
        $dashboardSub = new Dashboard();
        $dashboardSub->setParent($dashboard);
        $dashboardSub->setCapability('edit_pages');
        $manager->addMenuItem($dashboardSub);

        // section manager
        $dashboardSub = new SectionItemManager();
        $dashboardSub->setParent($dashboard);
        $dashboardSub->setCapability('edit_pages');
        $manager->addMenuItem($dashboardSub);
        
        // menu manager
        $dashboardSub = new MenuSectionManager();
        $dashboardSub->setParent($dashboard);
        $dashboardSub->setCapability('edit_pages');
        $manager->addMenuItem($dashboardSub);
        
        // create new menu
        //$dashboardSub = new NewMenu();
        //$dashboardSub->setParent($dashboard);
        //$dashboardSub->setCapability('edit_pages');
        //$manager->addMenuItem($dashboardSub);
        
        // Menu Tags
        //$dummyItem = new MenuItem();
        //$dummyItem->setMenuSlug('edit.php?post_type=fl-menu-item');
        //'edit.php?post_type=fl-menu-item',
        $dashboardSub = new MenuTags();
        //$dashboardSub->setParent($dummyItem);
        $dashboardSub->setParent($dashboard);
        $dashboardSub->setCapability('edit_pages');
        $dashboardSub->setFunction(array(new TagsPage, 'content'));
        $manager->addMenuItem($dashboardSub);
        
        // settings
        $dashboardSub = new Settings();
        $dashboardSub->setParent($dashboard);
        $dashboardSub->setCapability('edit_pages');
        $dashboardSub->setFunction(array(new SettingsPage($dashboardSub), 'content'));
        new MetaboxScreen($dashboardSub);
        $manager->addMenuItem($dashboardSub);

        do_action('wpfl_wp_menu', $manager);
        
        $manager->setupAdminMenuHook();
    }

    public function addEditorButtons()
    {
        echo '
            <style>
                #add-fl-menu .flmenu-media-icon {
                    background:url(' . $this->getManager()->getPluginUrl() . '/assets/images/menu-icons.png) no-repeat top left;
                    background-position: -40px -39px;
                    display: inline-block;
                    height: 16px;
                    margin: 0 2px 0 0;
                    vertical-align: text-top;
                    width: 16px;
                }
                #add-fl-menu:hover .flmenu-media-icon {
                    background-position: -40px -7px;
                }
                .wp-core-ui a.flmenu_media_link {
                     padding-left: 0.4em;
                }
            </style>
            <a href="#TB_inline?width=480&inlineId=foodlist-select-menu"
               class="thickbox button flmenu_media_link"
               id="add-fl-menu"
               title="' . __('Add Menu', 'foodlist') . '"
            >
                <span class="flmenu-media-icon"></span> ' . __('Add Menu', 'foodlist') . '
           </a>
        ';
    }

    public function addMcePopup(){
        ?>
        <script>
            function flInsertMenu(){
                var menu_id = jQuery("#fl-menu-selector").val();
                if(menu_id == ""){
                    alert("<?php _e('Please select a menu', 'foodlist') ?>");
                    return;
                }

                var menu_name = jQuery("#fl-menu-selector > option[value='" + menu_id + "']").text().replace(/[\[\]]/g, '');
                var display_title = jQuery("#fl-display-title").is(":checked");
                var title_qs = !display_title ? " notitle=\"true\"" : "";

                window.send_to_editor("[flmenu id=\"" + menu_id + "\" name=\"" + menu_name + "\"" + title_qs + "]");
            }

            jQuery('head').append('\
                <style>\
                    #TB_ajaxContent .wrap h3 {\
                        color: #5A5A5A !important;\
                        font-family: Georgia,Times New Roman,Times,serif !important;\
                        font-size: 1.8em !important;\
                        font-weight: normal !important;\
                    }\
                    #TB_ajaxContent div.fl-menu-settings {\
                        padding: 15px 15px 0 15px;\
                    }\
                </style>\
            ');
        </script>

        <div id="foodlist-select-menu" style="display: none;">
            <div class="wrap">
                <div class="fl-menu-settings">
                    <h3><?php _e('Insert Menu', 'foodlist'); ?></h3>
                    <span>
                        <?php _e('Select a menu below to add it to your post or page.', 'foodlist'); ?>
                    </span>
                </div>
                <div class="fl-menu-settings">
                    <select id="fl-menu-selector">
                        <option value=""><?php _e('Select menu', 'foodlist'); ?></option>
                        <?php
                            $query = new \WP_Query(array(
                                'post_type' => 'fl-menu',
                                'post_status' => array('publish'),
                                'posts_per_page' => -1,
                                'order' => 'ASC',
                                'orderby' => 'title',
                            ));
                            if ($query->have_posts()) {
                                while ($query->have_posts()) {
                                    $query->the_post();
                                    echo '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
                                }
                            }
                            wp_reset_postdata();
                        ?>
                    </select>
                    <br/>
                    <div style="padding:8px 0 0 0; font-size:11px; font-style:italic; color:#5A5A5A">
                        <?php _e("Can't find your menu? Make sure it is published.", 'foodlist'); ?>
                    </div>
                </div>
                <div class="fl-menu-settings">
                    <label>
                        <input type="checkbox" id="fl-display-title" checked='checked' />
                        <?php _e('Display menu title', 'foodlist'); ?>
                    </label> &nbsp;&nbsp;&nbsp;
                </div>
                <div style="padding:15px;">
                    <input type="button" class="button-primary" value="Insert Menu" onclick="flInsertMenu();"/>&nbsp;&nbsp;&nbsp;
                    <a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e('Cancel', 'foodlist'); ?></a>
                </div>
            </div>
        </div>

    <?php
    }

    public function createMetaboxes()
    {
        do_action('fl_create_metaboxes_before');
        new CurrencyMetabox();
        new TemplatesMetabox();
        new CssEditorMetabox();
        new DataSettingsMetabox();
        do_action('fl_create_metaboxes_after');
    }
}
