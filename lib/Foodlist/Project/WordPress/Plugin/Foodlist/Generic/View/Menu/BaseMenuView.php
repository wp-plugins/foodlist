<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\View\Menu;

abstract class BaseMenuView
{
    protected $id;
    protected $instanceId;
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setInstanceId($id)
    {
        $this->instanceId = $id;
        return $this;
    }
    
    public function getInstanceId()
    {
        return $this->instanceId;
    }
    
    public function parseInstanceStr($instance)
    {
        $instance = str_replace('widget-post-', '', $instance);
        list($postId, $instanceId) = explode('-', $instance);
        return array((int)$postId, (int)$instanceId);
    }
    
    abstract public function getHtml();
}