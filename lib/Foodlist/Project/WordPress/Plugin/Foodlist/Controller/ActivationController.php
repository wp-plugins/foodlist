<?php
/**
 * @version   $Id$
 * @package   Foodlist
 * @copyright Copyright (C) 2013 Artprima / http://artprima.eu/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuItemPost;
use Artprima\WordPress\Helper\Settings as SettingsHelper;

class ActivationController extends BaseController
{
    
    /**
     * Inits controller
     */
    public function init()
    {
        // in fact it should be always true, it's just for making sure
        if (did_action('init')) {
            // because init was called before the plugin started, we need to prepare some structures
            // so that the plugin could install the demo data
            add_image_size('fl-menu-tag-icon', 16, 16, true);
            add_image_size('fl-menu-item-thumb', 100, 100, true);

            fl_setup_wpdb_meta_table();
            $this->loadTaxonomies();
            $this->loadCustomPosts();
        }

        if (get_option('foodlist_first_start', 1)) {
            $this->createDBTables();
            $this->setupDemoData();
            $this->setupTemplates();
            update_option('foodlist_first_start', 0);
        }

        // register taxonomies/post types here
        flush_rewrite_rules();

        return $this;
    }

    /**
     * Explicitly calls onInit for all the plugin's registered taxonomies
     * this is normally called at 'init' action, but is not during the activation process
     */
    private function loadTaxonomies()
    {
        $taxonomies = $this->getManager()->get('taxonomies', array());

        foreach ($taxonomies as $taxonomy) {
            $taxonomy->onInit();
        }
    }

    /**
     * Explicitly calls onInit for all the plugin's registered posts
     * this is normally called at 'init' action, but is not during the activation process
     */
    private function loadCustomPosts()
    {
        $posts = $this->getManager()->get('custom_posts', array());

        foreach ($posts as $post) {
            $post->onInit();
        }
    }

    /**
     * Prepare custom tables
     * 
     * @global \wpdb $wpdb
     */
    private function createDBTables()
    {
        global $wpdb;

        $wpdb->query('
            CREATE TABLE IF NOT EXISTS `'.$wpdb->get_blog_prefix().'foodlist_menu_tagmeta` (
              `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `foodlist_menu_tag_id` bigint(20) unsigned NOT NULL DEFAULT "0",
              `meta_key` varchar(255) DEFAULT NULL,
              `meta_value` longtext,
              PRIMARY KEY (`meta_id`),
              KEY `foodlist_menu_tag_id` (`foodlist_menu_tag_id`),
              KEY `meta_key` (`meta_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ');
    }
    
    /**
     * Default menu item tags data
     * 
     * @return array
     */
    private function getDemoMenuItemTags()
    {
        $tags = array(
            array(
                'name' => __('Healthy', 'foodlist'),
                'description' => __('Healthy', 'foodlist'),
                'slug' => __('healthy', 'foodlist'),
                'url' => $this->getManager()->getPluginUrl().'/assets/images/icons/healthy.png',
            ),
            array(
                'name' => __('Spicy', 'foodlist'),
                'description' => __('Spicy', 'foodlist'),
                'slug' => __('spicy', 'foodlist'),
                'url' => $this->getManager()->getPluginUrl().'/assets/images/icons/pepper.png',
            ),
            array(
                'name' => __('Star', 'foodlist'),
                'description' => __('Star', 'foodlist'),
                'slug' => __('star', 'foodlist'),
                'url' => $this->getManager()->getPluginUrl().'/assets/images/icons/star.png',
            ),
            array(
                'name' => __('Vegetarian', 'foodlist'),
                'description' => __('Vegetarian', 'foodlist'),
                'slug' => __('vegetarian', 'foodlist'),
                'url' => $this->getManager()->getPluginUrl().'/assets/images/icons/vegetarian.png',
            ),
            array(
                'name' => __('Award Winning', 'foodlist'),
                'description' => __('Award Winning', 'foodlist'),
                'slug' => __('trophy', 'foodlist'),
                'url' => $this->getManager()->getPluginUrl().'/assets/images/icons/trophy.png',
            ),
            array(
                'name' => __('New', 'foodlist'),
                'description' => __('New', 'foodlist'),
                'slug' => __('new', 'foodlist'),
                'url' => $this->getManager()->getPluginUrl().'/assets/images/icons/new.png',
            ),
        );
        
        return apply_filters('foodlist_demo_menu_item_tags', $tags);
    }
    
    private function getDemoMenuItems()
    {
        $items = array(
            array(
                'name' => __('Antipasto', 'foodlist'),
                'excerpt' => __('Antipasto (plural antipasti) means "before the meal" and is the traditional first course of a formal Italian meal.', 'foodlist'),
                'price' => '10',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/antipasto.jpg',
                'tags' => 'star',
                'section' => 'appetizers',
            ),
            array(
                'name' => __('Caesar Salad', 'foodlist'),
                'excerpt' => __('The Almost Traditional Recipe with Croutons, Parmesan Cheese and Our Special Caesar Dressing. Available with Chicken.', 'foodlist'),
                'price' => '11.5',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/caesar.jpg',
                'tags' => 'healthy,vegetarian',
                'section' => 'appetizers',
            ),
            array(
                'name' => __('Chicken Rice Soup', 'foodlist'),
                'excerpt' => __('This is a great, easy chicken-and-rice soup. We like to use instant brown rice because it cooks so quickly, but you could substitute cooked brown rice.', 'foodlist'),
                'price' => '3.95',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/chicken-soup.jpg',
                'section' => 'main-course',
            ),
            array(
                'name' => __('Coca-Cola', 'foodlist'),
                'excerpt' => __('Coca-Cola is a carbonated soft drink sold in stores, restaurants, and vending machines throughout the world.', 'foodlist'),
                'price' => '1.5',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/coca-cola.jpg',
                'section' => 'drinks',
            ),
            array(
                'name' => __('Sprite', 'foodlist'),
                'excerpt' => __('Sprite is a colorless, lemon-lime flavored, caffeine-free soft drink, created by the Coca-Cola Company.', 'foodlist'),
                'price' => '1.4',
                'section' => 'drinks',
            ),
            array(
                'name' => __('Mirinda', 'foodlist'),
                'excerpt' => __('Mirinda is a brand of soft drink originally created in Spain, with global distribution. The word Mirinda means "admirable" or "wonderful" in Esperanto.', 'foodlist'),
                'price' => '1.4',
                'section' => 'drinks',
            ),
            array(
                'name' => __('Dessert', 'foodlist'),
                'excerpt' => __('Dessert is a typically sweet course that concludes a meal. The course usually consists of sweet foods, but may include other items.', 'foodlist'),
                'price' => '3.5',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/dessert.jpg',
                'tags' => 'new',
                'section' => 'desserts',
            ),
            array(
                'name' => __('Fried Shrimp', 'foodlist'),
                'excerpt' => __('Pan fry shrimp for a quick weeknight dinner or an impromptu dinner party with a few close family members and friends.', 'foodlist'),
                'price' => '6.95',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/fried-shrimp.jpg',
                'tags' => 'trophy',
                'section' => 'appetizers',
            ),
            array(
                'name' => __('Hot and Sour Soup', 'foodlist'),
                'excerpt' => __('Hot and Sour Soup - the classic Szechuan recipe for hot and sour soup, made with tofu, pork and dried mushrooms.', 'foodlist'),
                'price' => '4.25',
                'image' => $this->getManager()->getPluginDir().'/assets/images/samples/hot-n-sour-soup.jpg',
                'tags' => 'spicy',
                'section' => 'main-course',
            ),
        );
        
        return apply_filters('foodlist_demo_menu_items', $items);
    }

    private function getDemoMenuSections()
    {
        $items = array(
            array(
                'id' => 'desserts',
                'name' => __('Desserts', 'foodlist'),
                'excerpt' => __('Dessert is a typically sweet course that concludes a meal. The course usually consists of sweet foods, but may include other items.', 'foodlist'),
                'menu' => 'main-menu',
            ),
            array(
                'id' => 'appetizers',
                'name' => __('Appetizers', 'foodlist'),
                'excerpt' => __('Hors d\'oeuvre or the first course, are food items served before the main courses of a meal.', 'foodlist'),
                'menu' => 'main-menu',
            ),
            array(
                'id' => 'main-course',
                'name' => __('Main course', 'foodlist'),
                'excerpt' => __('A main course is the featured or primary dish in a meal consisting of several courses. It usually follows the entrée ("entry") course. In the United States it may in fact be called "entree".', 'foodlist'),
                'menu' => 'main-menu',
            ),
            array(
                'id' => 'drinks',
                'name' => __('Non-alcoholic Drinks', 'foodlist'),
                'excerpt' => __('A non-alcoholic beverage (also known as a virgin drink) is defined in the U.S. as a beverage that contains less than 0.5% alcohol by volume.', 'foodlist'),
                'menu' => 'main-menu',
            ),
        );
        
        return apply_filters('foodlist_demo_menu_sections', $items);
    }
    
    private function getDemoMenus()
    {
        $items = array(
            array(
                'id' => 'main-menu',
                'name' => __('Main Menu', 'foodlist'),
                'excerpt' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent eleifend diam facilisis ultricies interdum. Suspendisse ac nisl urna. Donec non commodo leo. Aenean ipsum elit, placerat in placerat at, scelerisque vel ligula. Mauris nec libero nec augue posuere mattis. Cras feugiat felis justo. Aenean ac interdum dolor. Aenean convallis vestibulum orci eget sagittis. Integer fringilla posuere odio ut accumsan. Nam sit amet sapien tellus. Nulla eu placerat felis.', 'foodlist'),
            ),
        );
        
        return apply_filters('foodlist_demo_menus', $items);
    }
    
    private function attachImage($filename, $parent)
    {
        $content = file_get_contents($filename);
        $data = wp_upload_bits(basename($filename), null, $content);
        $filetype = wp_check_filetype(basename($data['file']));
        if (!empty($data['error'])) {
            return false;
        }
        $attachment = array(
            'guid' => $data['url'],
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($data['file'])),
            'post_content' => '',
            'post_status' => 'inherit',
        );
        $attach_id = wp_insert_attachment($attachment, $data['file'], $parent);

        // you must first include the image.php file
        // for the function wp_generate_attachment_metadata() to work
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $data['file']);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_post_thumbnail($parent, $attach_id);
        return $attach_id;
    }
    
    public function setupDemoData()
    {
       //$term = term_exists('fl_menu_tag', __('healthy', 'foodlist')); // array is returned if taxonomy is given
        
        $demoData = array(
            'tags' => array(),
            'items' => array(),
            'sections' => array(),
            'menus' => array(),
            'attachments' => array(),
        );

        // 1. Create Tags
        foreach ($this->getDemoMenuItemTags() as $tag) {
            $data = wp_insert_term(
                $tag['name'], // the term 
                'fl-menu-tag', // the taxonomy
                array(
                    'description'=> $tag['description'], 
                    'slug' => $tag['slug'],
                )
            );

            /* @todo need something to do if error encountered */
            if (!($data instanceof \WP_Error)) {
                update_foodlist_menu_tag_meta($data['term_id'], 'icon', $tag['url']);
                $demoData['tags'][] = $data['term_id'];
            } else {
                //var_dump($data);
            }
        }
        
        $itemsOrder = array();
        // @todo 2. Create Menu Items
        foreach ($this->getDemoMenuItems() as $item) {
            $post = wp_insert_post(array(
                'post_title' => $item['name'],
                'post_excerpt' => $item['excerpt'],
                'post_type' => 'fl-menu-item',
                'post_status' => 'publish',
            ));
            if ($post) {
                if (isset($item['tags'])) {
                    wp_set_post_terms($post, $item['tags'], 'fl-menu-tag');
                }
                if (isset($item['price'])) {
                    $mi = new MenuItemPost($post);
                    $mi->setPrice($item['price']);
                }
                if (isset($item['image'])) {
                    $attachment = $this->attachImage($item['image'], $post);
                    $demoData['attachments'][] = $attachment;
                }
                if (isset($item['section'])) {
                    foreach ((array)$item['section'] as $id => $section) {
                        $itemsOrder[$section][] = 'widget-post-'.$post.'-'.($id+1);
                        $demoData['items'][] = $post;
                    }
                }
                
            }
        }
        
        // prepare order strings for items
        $itemsOrderStr = array();
        foreach ($itemsOrder as $section => $itemsOrderSection) {
            $itemsOrderStr[$section] = implode(',', $itemsOrderSection);
        }
        
        $sectionsOrder = array();
        // @todo 3. Create Menu Sections
        foreach ($this->getDemoMenuSections() as $item) {
            $post = wp_insert_post(array(
                'post_title' => $item['name'],
                'post_excerpt' => $item['excerpt'],
                'post_type' => 'fl-menu-section',
                'post_status' => 'publish',
            ));
            if ($post) {
                if (isset($item['image'])) {
                    $attachment = $this->attachImage($item['image'], $post);
                    $demoData['attachments'][] = $attachment;
                }
                if (!empty($item['id']) && !empty($itemsOrderStr[$item['id']])) {
                    update_post_meta($post, '_fl_menu_items_order', $itemsOrderStr[$item['id']]);
                }
                if (isset($item['menu'])) {
                    foreach ((array)$item['menu'] as $id => $menu) {
                        $sectionsOrder[$menu][] = 'widget-post-'.$post.'-'.($id+1);
                        $demoData['sections'][] = $post;
                    }
                }
            }
        }
        
        // prepare order strings for sections
        $sectionsOrderStr = array();
        foreach ($sectionsOrder as $menu => $sectionsOrderSection) {
            $sectionsOrderStr[$menu] = implode(',', $sectionsOrderSection);
        }
        
        // @todo 4. Create Menu
        foreach ($this->getDemoMenus() as $item) {
            $post = wp_insert_post(array(
                'post_title' => $item['name'],
                'post_excerpt' => $item['excerpt'],
                'post_type' => 'fl-menu',
                'post_status' => 'publish',
            ));
            if ($post) {
                if (isset($item['image'])) {
                    $attachment = $this->attachImage($item['image'], $post);
                    $demoData['attachments'][] = $attachment;
                }
                if (!empty($item['id']) && !empty($sectionsOrderStr[$item['id']])) {
                    update_post_meta($post, '_fl_menu_sections_order', $sectionsOrderStr[$item['id']]);
                    $demoData['menus'][] = $post;
                }
            }
        }

        // save demo data ids to db, so that later we could delete the data with one click
        update_option('foodlist_demo_data_ids', $demoData);
    }
    
    public function setupTemplates()
    {
        $helper = SettingsHelper::getInstance('foodlist');
        $helper->set('template_menu', 
'<div class="fl-menu" id="fl-menu-[menu_id]">
    [menu_title]<h1>[menu_title_text]</h1>[/menu_title]
    <div class="fl-excerpt">
        [menu_excerpt]
    </div>
    <div class="clear"></div>
    <ul>
        [menu_sections]
        <li>
            [menu_section]
        </li>
        [/menu_sections]
    </ul>
</div>');
        
        $helper->set('template_menu_section', 
'<div class="fl-menu-section" id="fl-menu-section-[menu_section_id]-[menu_section_instance]">
    <h2>[menu_section_title]</h2>
    <div class="fl-excerpt">
        [menu_section_excerpt]
    </div>
    <div class="clear"></div>
    <ul>
        [menu_items]
        <li>
            [menu_item]
        </li>
        [/menu_items]
    </ul>
</div>');
        
        $helper->set('template_menu_item',
'<div class="fl-menu-item" id="fl-menu-item-[menu_item_id]-[menu_item_instance]">
    <h3>[menu_item_title]</h3>
    <div class="fl-menu-item-meta">
        [menu_item_tags]
            <img src="[menu_item_tag_icon_url]" alt="[menu_item_tag_description]" />
        [/menu_item_tags]
        <span class="fl-currency-sign">[currency_sign]</span>
        <span class="fl-menu-item-price">[menu_item_price]</span>
    </div>
    <div class="fl-excerpt">
        [menu_item_thumbnail]
        [menu_item_excerpt]
    </div>
    <div class="clear"></div>
</div>
');
        $helper->save();
        
    }
    
}