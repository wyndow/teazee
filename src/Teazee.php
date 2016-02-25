<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
interface Teazee
{
    /**
     * Version.
     */
    const VERSION = '0.4-dev';

    /**
     * Finds a TimeZone for a location on the surface of the earth.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int|null     $timestamp Seconds since Jan 1, 1970 UTC.
     *
     * @return ZoneInfo
     */
    public function find($lat, $lng, $timestamp = null);
}
