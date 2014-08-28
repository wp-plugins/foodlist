<?php

/*
 * This file is part of the ${ProjectName} package.
 *
 * (c) Denis Voytyuk <ask@artprima.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artprima\WordPress\API\Wrapper\Generic;

/**
 * Class Widget
 *
 * @author Denis Voytyuk <ask@artprima.cz>
 *
 * @package Artprima\WordPress\API\Wrapper\Generic
 */
class Widget extends \WP_Widget
{
    /**
     * Sets up wordpress init hook
     *
     * @return \Artprima\WordPress\API\Wrapper\Generic\CustomPost
     */
    public function setupRegistrationHook()
    {
        add_action('widgets_init', array($this, 'onWidgetsInit'));
        return $this;
    }

    /**
     * @usage private
     */
    public function onWidgetsInit()
    {
        register_widget(get_class($this));
    }

} 