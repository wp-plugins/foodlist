<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Artprima\Text\ShortcodeManager;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

class MenuItemThumbnailUrlShortcode extends Shortcode {
    
    public function getTag()
    {
        return 'menu_item_thumbnail_url';
    }
    
    public function apply($attrs, $content = null)
    {
        $cur = Manager::getInstance()->get('curitem');
        $postData = $cur->getPostData();
        $id = $postData['id'];

        $result = '';
        if (has_post_thumbnail($id)) {
            $thumbnailId = get_post_thumbnail_id($id);
            $data = wp_get_attachment_image_src(
                $thumbnailId,
                isset($attrs['size']) ? $attrs['size'] : 'thumbnail'
            );

            if ($data) {
                $result = $data[0];
            }
        }
        
        return $result;
    }
    
}