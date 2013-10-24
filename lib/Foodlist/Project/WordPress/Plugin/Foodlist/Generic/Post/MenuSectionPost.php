<?php
/**
 * @version   $Id$
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post;

class MenuSectionPost extends BasePost
{
    protected $instanceId;

    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    public function getInstanceId()
    {
        return $this->instanceId;
    }
}