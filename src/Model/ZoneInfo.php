<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Model;

use DateTimeImmutable;
use DateTimeZone;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
final class ZoneInfo extends DateTimeZone
{
    /**
     * @var string
     */
    private $country;

    /**
     * @var bool
     */
    private $dst;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var int
     */
    private $utcOffset;

    /**
     * @var DateTimeImmutable
     */
    private $dateTime;

    /**
     * TimeZone Constructor.
     *
     * @param string $timezone  Timezone identifier.
     * @param bool   $dst       Whether or not this TimeZone is in DST.
     * @param int    $utcOffset Offset from UTC.
     * @param int    $timestamp UNIX timestamp.
     * @param string $country   Country code.
     */
    public function __construct($timezone, $dst, $utcOffset, $timestamp, $country = null)
    {
        parent::__construct($timezone);

        $this->dst = $dst ? (bool) $dst : null;
        $this->utcOffset = $utcOffset ? (int) $utcOffset : null;
        $this->timestamp = $timestamp ? (int) $timestamp : null;
        $this->country = $country ?: $this->getLocation()['country_code'];

        if ($this->timestamp) {
            $this->dateTime = (new \DateTimeImmutable('@'.$this->timestamp))->setTimezone($this);
        }
    }

    /**
     * Returns the country code.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Returns a DateTimeImmutable for the provided timestamp.
     *
     * @return DateTimeImmutable
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Returns a UNIX timestamp.
     *
     * @return int|null
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Returns the UTC offset for this TimeZone.
     *
     * @return int|null
     */
    public function getUtcOffset()
    {
        return $this->utcOffset;
    }

    /**
     * Checks whether or not this TimeZone is on Daylight Savings Time.
     *
     * @return bool True when the TimeZone is on Daylight Savings Time.
     */
    public function isDst()
    {
        return $this->dst;
    }
}
