<?php

namespace Artprima\WordPress\API\Wrapper\Admin\Metabox;


abstract class PostMetabox extends Metabox {

    public function init()
    {
        add_action('save_post', array($this, 'onPostSave'));
        return parent::init();
    }
    abstract function onPostSave($postId);
    
}