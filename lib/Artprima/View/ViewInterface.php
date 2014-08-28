<?php

/*
 * This file is part of the ${ProjectName} package.
 *
 * (c) Denis Voytyuk <ask@artprima.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artprima\View;

/**
 * Interface ViewInterface
 *
 * @author Denis Voytyuk <ask@artprima.cz>
 *
 * @package Artprima\View
 */
interface ViewInterface
{
    /**
     * Renders the view
     *
     * @return string
     */
    public function render();
} 