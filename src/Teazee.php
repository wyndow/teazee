<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee;

interface Teazee
{
    const VERSION = '0.1.0-dev';

    public function find($lat, $lng, $timestamp);
}
