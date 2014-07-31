<?php

/*
 * This file is part of the Foodlist package.
 *
 * (c) Denis Voytyuk <ask@artprima.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal;

use Artprima\Text\Shortcode;
use Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Post\MenuPost;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager;

/**
 * Class MenuPermalinkShortcode
 *
 * @author Denis Voytyuk <ask@artprima.cz>
 *
 * @package Foodlist\Project\WordPress\Plugin\Foodlist\Generic\Shortcode\Internal
 */
class MenuPermalinkShortcode extends Shortcode
{

    public function getTag()
    {
        return 'menu_permalink';
    }

    public function apply($attrs, $content = null)
    {
        /** @var MenuPost $cur */
        $cur = Manager::getInstance()->get('curmenu');
        $postData = $cur->getPostData();
        $permalink = esc_url(get_permalink($postData['id']));
        return $permalink;
    }

} 