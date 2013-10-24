<?php

namespace Artprima\WordPress\API\Wrapper\Admin\UI;

class AdminPointer
{

    /**
     *
     * @var AdminPointer 
     */
    protected static $instance;
    
    protected $pointers = array();
    protected $pointersGlobal = array();
    
    protected function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'handle'), 1000);
        //add_filter('wptuts_admin_pointers-post', array($this, 'wptuts_register_pointer_testing'));
    }
    
    /**
     * 
     * @return AdminPointer
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function setScriptUrl($url)
    {
        $this->scriptPath = $url;
        return $this;
    }
    
    public function getScriptUrl()
    {
        return $this->scriptPath ? $this->scriptPath : plugins_url('assets/artprimalib/js/admin-pointers.js', 'foodlist/plugin.php');
    }
    
    /**
     * 
     *  <code>
     *  $pointers['xyz140'] = array(
     *      //'target' => '#change-permalinks',
     *      'target' => '#editable-post-name',
     *      'options' => array(
     *          'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
     *              __( 'Title' ,'plugindomain'),
     *              __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','plugindomain')
     *          ),
     *          'position' => array( 'edge' => 'top', 'align' => 'middle' )
     *      )
     * );
     * </code>
     * 
     * @param string $name
     * @param array $data
     * @param string $screenId
     * @return \Artprima\WordPress\API\Wrapper\Admin\UI\AdminPointer
     */
    public function add($name, $data, $screenId = '')
    {
        //$hook = $screenId ? 'artprima_admin_pointers-'.$screenId : 'artprima_admin_pointers';
        /*
        add_filter($hook, function() use ($name, $data) {
            return $pointers;
        });
         * 
         */
        if ($screenId) {
            $this->pointers[$screenId][$name] = $data;
        } else {
            $this->pointersGlobal[$name] = $data;
        }
        return $this;
    }
    
    public function handle()
    {
        $screen = get_current_screen();
        $screen_id = $screen->id;

        // Get pointers for this screen
        //$pointers = apply_filters('artprima_admin_pointers-'.$screen_id, array());
        //$pointersGlobal = apply_filters('artprima_admin_pointers', array());

        $pointersScreen = isset($this->pointers[$screen_id]) ? $this->pointers[$screen_id] : array();
        
        $pointers = array_merge($pointersScreen, $this->pointersGlobal);

        if (!$pointers || !is_array($pointers)) {
            return;
        }

        // Get dismissed pointers
        //delete_user_meta(get_current_user_id(), 'dismissed_wp_pointers');
        $dismissed = explode(',', (string)get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
        $valid_pointers = array();

        // Check pointers and remove dismissed ones.
        foreach ($pointers as $pointer_id => $pointer) {

            // Sanity check
            if (in_array($pointer_id, $dismissed) || empty($pointer) || empty($pointer_id) || empty($pointer['target']) || empty($pointer['options'])) {
                continue;
            }

            $pointer['pointer_id'] = $pointer_id;

            // Add the pointer to $valid_pointers array
            $valid_pointers['pointers'][] =  $pointer;
        }

        // No valid pointers? Stop here.
        if (empty($valid_pointers)) {
            return;
        }

        // Add pointers style to queue.
        wp_enqueue_style('wp-pointer');

        // Add pointers script to queue. Add custom script.
        wp_enqueue_script('ap-admin-pointers', $this->getScriptUrl(), array('wp-pointer'));

        // Add pointer options to script.
        wp_localize_script('ap-admin-pointers', 'apAdminPointers', $valid_pointers);
    }
    
    /*
    public function wptuts_register_pointer_testing($p) {
        $p['xyz140'] = array(
            //'target' => '#change-permalinks',
            'target' => '#editable-post-name',
            'options' => array(
                'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                    __( 'Title' ,'plugindomain'),
                    __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.','plugindomain')
                ),
                'position' => array( 'edge' => 'top', 'align' => 'middle' )
            )
        );
        return $p;
    }
    */
}