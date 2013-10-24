<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

class UpgraderController extends BaseController
{
    
    /**
     * Inits controller
     */
    public function init()
    {
        $dbVersion = get_option('foodlist_last_version', '0.0');
        if (version_compare(FOODLIST_VERSION, $dbVersion, '>')) {
            
            if ($dbVersion == '0.0') {
                // this is the first time the plugin is ran -- do nothing
            }
            
        }
        return $this;
    }
    
}