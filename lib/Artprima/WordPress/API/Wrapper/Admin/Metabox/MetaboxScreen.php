<?php

namespace Artprima\WordPress\API\Wrapper\Admin\Metabox;

use Artprima\WordPress\API\Wrapper\Admin\Menu\MenuItemInterface;

class MetaboxScreen {
    
    protected $menuItem;

	public function __construct(MenuItemInterface $menuItem) {
        $this->menuItem = $menuItem;
        $function = $this->menuItem->getFunction();
        $self = $this;
        $this->menuItem->setFunction(function() use ($function, $self){
            call_user_func($function);
            $self->onShowPage();
        });
        
		add_filter('screen_layout_columns', array($this, 'onScreenLayoutColumns'), 10, 2);
		add_action('admin_menu', array($this, 'onAdminMenu'), 11); // after all menus loaded
	}
	
	//for WordPress 2.8 we have to tell, that we support 2 columns !
	public function onScreenLayoutColumns($columns, $screen) {
		//bugfix: $this->pagehook is not valid because it will be set at hook 'admin_menu' but 
		//multisite pages or user dashboard pages calling different menu an menu hooks!
		//if (!defined( 'WP_NETWORK_ADMIN' ) && !defined( 'WP_USER_ADMIN' )) {
			if ($screen == $this->pagehook) {
		//		$columns[$this->pagehook] = 2;
			}
		//}
		return $columns;
	}
	
	//extend the admin menu
	public function onAdminMenu() {
		//add our own option page, you can also add it to different sections or use your own one
		//$this->pagehook = add_options_page('Howto Metabox Page Title', "HowTo Metaboxes", 'manage_options', HOWTO_METABOX_ADMIN_PAGE_NAME, array($this, 'on_show_page'));
        $this->pagehook = $this->menuItem->get();
		//register  callback gets call prior your own page gets rendered
		add_action('load-'.$this->pagehook, array($this, 'onLoadPage'));
	}
	
	//will be executed if wordpress core detects this page has to be rendered
	function onLoadPage() {
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
	}
	
	//executed to show the plugins complete admin page
	public function onShowPage() {
        ?>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready( function($) {
                // close postboxes that should be closed
                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                // postboxes setup
                postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
            });
            //]]>
        </script>
		<?php
	}
	
}
