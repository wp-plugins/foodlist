<?php
/**
 * @version   $Id: Base.php 339 2013-08-29 18:34:32Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Controller;

use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

abstract class BaseController
{
    /**
     * Manager instance
     * @var Manager
     */
    protected $manager;
    
    /**
     * Constructor
     * 
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get Manager instance
     * 
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }
    
    abstract public function init();
}
