<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItem;

class SectionManager extends MenuItem
{
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getPageTitle()
    {
        if ($this->pageTitle === null) {
            return __('Section Manager', 'foodlist');
        }
        return $this->pageTitle;
    }
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getMenuTitle()
    {
        if ($this->menuTitle === null) {
            return __('Section Manager', 'foodlist');
        }
        return $this->menuTitle;
    }
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getMenuSlug()
    {
        if ($this->menuSlug === null) {
            return 'foodlist-section-manager';
        }
        return $this->menuSlug;
    }

    public function getItem($instanceId = '__i__', $add_new = 'multi')
    {
        $postId = get_the_ID();
        $widget_number = '-1';
        
        $multi_number = get_post_meta($postId, '_fl_menu_item_multi_number', true);
        if (!$multi_number) {
            $multi_number = '1';
        }
        
        $item = '
            <div class="widget ui-draggable" id="widget-post-'.$postId.'-'.$instanceId.'">
                <div class="widget-top">
                    <div class="widget-title">
                        <h4>'.esc_attr(strip_tags(get_the_title())).'<span class="in-widget-title"></span></h4>
                    </div>
                </div>

                <div class="widget-inside">
                    <form action="" method="post">
                        <div class="widget-content">
                        </div>
                        <input type="hidden" name="args[post_id]" value="'.$postId.'" />
                        <input type="hidden" name="args[widget_number]" class="widget_number" value="'.esc_attr($widget_number).'" />
                        <input type="hidden" name="args[multi_number]" class="multi_number" value="'.esc_attr($multi_number).'" />
                        <input type="hidden" name="args[add_new]" class="add_new" value="'.esc_attr($add_new).'" />
                        <div class="widget-control-actions">
                            <br class="clear" />
                        </div>
                    </form>
                </div>

                <div class="widget-description">
                    '.get_the_excerpt().'
                </div>
            </div>
        ';
        return $item;
    }
    
    public function getItemsList()
    {
        // The Query
        $query = new \WP_Query(array(
            'post_type' => 'fl-menu-item',
            'post_status' => array('draft', 'publish'),
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'title',
        ));
        
        $result = '';
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                $result .= $this->getItem();
            }
        }
        wp_reset_postdata();
        
        return $result;
    }
    
    public function getSections($sectionId=null)
    {
        // The Query
        $query = new \WP_Query(array(
            'p' => $sectionId,
            'post_type' => 'fl-menu-section',
            'post_status' => array('draft', 'publish'),
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'title',
        ));
        
        $result = '';
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $result .= '
                    <div class="widgets-holder-wrap" id="widget-post-'.get_the_ID().'">
                        <div class="sidebar-name">
                            <div class="sidebar-name-arrow"><br/></div>
                            <h3>'.esc_attr(strip_tags(get_the_title())).' <span class="spinner"></span></h3>
                        </div>
                        <div id="fl-menu-section-'.get_the_ID().'" class="widgets-sortables ui-sortable" data-item-id="'.get_the_ID().'">
                            <div class="sidebar-description">
                                <p class="description">'.get_the_excerpt().'</p>
                            </div>
                            '.$this->getSectionItems(get_the_ID()).'
                        </div>
                    </div>
                ';
            }
        }
        wp_reset_postdata();
        
        return $result;
    }

    public function parseInstanceStr($instance)
    {
        $instance = str_replace('widget-post-', '', $instance);
        list($postId, $instanceId) = explode('-', $instance);
        return array((int)$postId, (int)$instanceId);
    }
    
    public function getSectionItems($sectionId)
    {
        $result = '';
        $order = get_post_meta($sectionId, '_fl_menu_items_order', true);
        
        if (empty($order)) {
            return $result;
        }

        $items = explode(',', $order);
        
        if (!empty($items) && is_array($items)) {
            foreach ($items as $instance) {
                list($postId, $instanceId) = $this->parseInstanceStr($instance);
                $query = new \WP_Query(array(
                    'post_type' => 'fl-menu-item',
                    'post_status' => array('draft', 'publish'),
                    'posts_per_page' => 1,
                    'order' => 'ASC',
                    'orderby' => 'title',
                    'p' => $postId,
                ));

                if ($query->have_posts()) {
                    $query->the_post();
                    $result .= $this->getItem($instanceId, '');
                }
                wp_reset_postdata();
            }
        }
        
        return $result;
    }
    
    /**
     * 
     */
    public function printContent()
    {
        $sectionId = empty($_GET['fl-menu-section']) ? null : $_GET['fl-menu-section'];
        $sections = trim($this->getSections($sectionId));
        if (empty($sections)) {
            $sections = '
                <div class="widgets-holder-wrap empty-container">
                    <div class="empty-container-info">
                        <p class="description">'.sprintf(__('Please <a href="%s">create at least one section</a> to proceed.', 'foodlist'), admin_url('post-new.php?post_type=fl-menu-section')).'</p>
                    </div>
                </div>
            ';
        }
        $items = trim($this->getItemsList());
        if (empty($items)) {
            $items = '<p class="description">'.sprintf(__('Please <a href="%s">create at least one item</a> to proceed.', 'foodlist'), admin_url('post-new.php?post_type=fl-menu-item')).'</p>';
        } else {
            $items = '
                <p class="description">'.__('Drag Menu Items from here to a section on the right to use them. Drag items back here to deactivate them and delete their settings.', 'foodlist').'</p>
                <div id="widget-list">
                    '.$items.'
                </div>
            ';
        }
        
        echo '
            <div class="wrap">
                '.get_screen_icon().'
                <h2 class="nav-tab-wrapper">
                    <a class="nav-tab nav-tab-active">'.__('Section Manager', 'foodlist').'</a>
                    <a class="nav-tab" href="'.admin_url('admin.php?page=foodlist-menu-manager').'">'.__('Menu Manager', 'foodlist').'</a>
                </h2>
                <div class="widget-liquid-left">
                    <div id="widgets-left">
                        <div id="available-widgets" class="widgets-holder-wrap ui-droppable">
                            <div class="sidebar-name">
                                <div class="sidebar-name-arrow"><br/></div>
                                <h3>'.__('Available Menu Items', 'foodlist').'
                                    <span id="removing-widget" style="display: none;">'.__('Remove', 'foodlist'). ' <span></span></span>                                    
                                </h3>
                            </div>
                            <div class="widget-holder">
                            '.$items.'
                            </div>
                            <br class="clear" />
                        </div>
                    </div>
                </div>
                <div class="widget-liquid-right">
                    <div id="widgets-right">
                        '.$sections.'
                    </div>
                </div>
                <div id="fl-manager-context" data-context="section"></div>
                <form action="" method="post">
                    '.wp_nonce_field('save-section-items', '_wpnonce_section', false, false).'
                </form>
            </div>
        ';
    }
    
}