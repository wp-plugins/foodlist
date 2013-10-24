<?php
/**
 * @version   $Id: Menu.php 349 2013-09-21 22:09:02Z denis $
 * @package   Food List
 * @copyright Copyright (C) 2013 Food List Team / http://foodlist.site/. All rights reserved.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post;

class MenuPost extends BasePost
{
    public function __construct($postId)
    {
        $this->postId = $postId;
    }
}