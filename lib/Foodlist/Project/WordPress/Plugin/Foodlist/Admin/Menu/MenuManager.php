<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Menu;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItem;

class MenuManager extends MenuItem
{
    
    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getPageTitle()
    {
        if ($this->pageTitle === null) {
            return __('Menu Manager', 'foodlist');
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
            return __('Menu Manager', 'foodlist');
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
            return 'foodlist-menu-manager';
        }
        return $this->menuSlug;
    }

    public function getSection($instanceId = '__i__', $add_new = 'multi')
    {
        $postId = get_the_ID();
        $widget_number = '-1';
        
        $multi_number = get_post_meta($postId, '_fl_menu_section_multi_number', true);
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
    
    public function getSectionsList()
    {
        // The Query
        $query = new \WP_Query(array(
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
                
                $result .= $this->getSection();
            }
        }
        wp_reset_postdata();
        
        return $result;
    }
    
    public function getMenus($menuId=null)
    {
        // The Query
        $query = new \WP_Query(array(
            'p' => $menuId,
            'post_type' => 'fl-menu',
            'post_status' => array('draft', 'publish'),
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'title',
        ));
        
        $result = '';
        
        if ($query->have_posts()) {
            $i = 1;
            $result1 = '';
            $result2 = '';
            while ($query->have_posts()) {
                $query->the_post();
                $closed = $i > 1 ? ' closed' : '';
                $col = (++$i % 2)+1;


                ${'result'.$col} .= '
                    <div class="widgets-holder-wrap'.$closed.'" id="widget-post-'.get_the_ID().'">
                        <div class="sidebar-name">
                            <div class="sidebar-name-arrow"><br/></div>
                            <h3>'.esc_attr(strip_tags(get_the_title())).' <span class="spinner"></span></h3>
                        </div>
                        <div id="fl-menu-'.get_the_ID().'" class="widgets-sortables ui-sortable" data-item-id="'.get_the_ID().'">
                            <div class="sidebar-description">
                                <p class="description">'.get_the_excerpt().'</p>
                            </div>
                            '.$this->getMenuSections(get_the_ID()).'
                        </div>
                    </div>
                ';
            }

            for ($j = 1; $j <= 2; $j++) {
                $result .= '<div class="sidebars-column-'.$j.'">';
                $result .= ${'result'.$j};
                $result .= '</div>';
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
    
    public function getMenuSections($menuId)
    {
        $result = '';
        //$items = get_post_meta($menuId, '_fl_menu_sections', true);
        $order = get_post_meta($menuId, '_fl_menu_sections_order', true);
        
        if (empty($order)) {
            return;
        }

        $items = explode(',', $order);
        
        if (!empty($items) && is_array($items)) {
            foreach ($items as $instance) {
                list($postId, $instanceId) = $this->parseInstanceStr($instance);
                $query = new \WP_Query(array(
                    'post_type' => 'fl-menu-section',
                    'post_status' => array('draft', 'publish'),
                    'posts_per_page' => 1,
                    'order' => 'ASC',
                    'orderby' => 'title',
                    'p' => $postId,
                ));
                if ($query->have_posts()) {
                    $query->the_post();
                    $result .= $this->getSection($instanceId, '');
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
        $menuId = empty($_GET['fl-menu']) ? null : $_GET['fl-menu'];
        $menus = trim($this->getMenus($menuId));
        if (empty($menus)) {
            $menus = '
                <div class="widgets-holder-wrap empty-container">
                    <div class="empty-container-info">
                        <p class="description">'.sprintf(__('Please <a href="%s">create at least one menu</a> to proceed.', 'foodlist'), admin_url('post-new.php?post_type=fl-menu')).'</p>
                    </div>
                </div>
            ';
        }
        $sections = trim($this->getSectionsList());
        if (empty($sections)) {
            $sections = '<p class="description">'.sprintf(__('Please <a href="%s">create at least one section</a> to proceed.', 'foodlist'), admin_url('post-new.php?post_type=fl-menu-section')).'</p>';
        } else {
            $sections = '
                <p class="description">'.__('Drag Menu Sections from here to a menu on the right to use them. Drag sections back here to deactivate them and delete their settings.', 'foodlist').'</p>
                <div id="widget-list">
                    '.$sections.'
                </div>
            ';
        }
        echo '
            <div class="wrap">
                '.get_screen_icon().'
                <h2 class="nav-tab-wrapper">
                    <a class="nav-tab" href="'.admin_url('admin.php?page=foodlist-section-manager').'">'.__('Section Manager', 'foodlist').'</a>
                    <a class="nav-tab nav-tab-active">'.__('Menu Manager', 'foodlist').'</a>
                </h2>
                <div class="widget-liquid-left">
                    <div id="widgets-left">
                        <div id="available-widgets" class="widgets-holder-wrap ui-droppable">
                            <div class="sidebar-name">
                                <div class="sidebar-name-arrow"><br/></div>
                                <h3>'.__('Available Menu Sections', 'foodlist').'
                                    <span id="removing-widget" style="display: none;">'.__('Remove', 'foodlist'). ' <span></span></span>                                    
                                </h3>
                            </div>
                            <div class="widget-holder">
                                '.$sections.'
                            </div>
                            <br class="clear" />
                        </div>
                    </div>
                </div>
                <div class="widget-liquid-right">
                    <div id="widgets-right">
                        '.$menus.'
                    </div>
                </div>
                <div id="fl-manager-context" data-context="menu"></div>
                <form action="" method="post">
                    '.wp_nonce_field('save-menu-sections', '_wpnonce_menu', false, false).'
                </form>
            </div>
        ';
    }
    
}