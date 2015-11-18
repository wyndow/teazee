<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee;

use Teazee\Model\ZoneInfo;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
interface Teazee
{
    /**
     * Version
     */
    const VERSION = '0.2.0';

    /**
     * Finds a TimeZone for a location on the surface of the earth.
     *
     * @param string|float $lat       Latitude coordinate.
     * @param string|float $lng       Longitude coordinate.
     * @param int          $timestamp Timestamp used to determine daylight savings time.
     *
     * @return ZoneInfo
     */
    public function find($lat, $lng, $timestamp);
}
