<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Provider;

use Teazee\Teazee;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
interface Provider extends Teazee
{
    /**
     * Returns the name of this provider.
     *
     * @return string
     */
    public function getName();
}
