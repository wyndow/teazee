<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee;

use Teazee\Model\TimeZone;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
interface Teazee
{
    /**
     * Version
     */
    const VERSION = '0.1.0-dev';

    /**
     * Finds a TimeZone for a location on the surface of the earth.
     *
     * @param string|float $lat       Latitude coordinate.
     * @param string|float $lng       Longitude coordinate.
     * @param int          $timestamp Timestamp used to determine daylight savings time.
     *
     * @return TimeZone
     */
    public function find($lat, $lng, $timestamp);
}
