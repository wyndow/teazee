<?php

namespace Teazee\Model;

use DateTimeZone;

final class TimeZone
{
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
     * @var \DateTimeZone
     */
    private $dateTimeZone;

    /**
     * TimeZone Constructor.
     *
     * @param DateTimeZone $zone
     * @param bool $dst Whether or not this TimeZone is in DST.
     * @param int $utcOffset Offset from UTC.
     * @param int $timestamp UNIX timestamp.
     */
    public function __construct(DateTimeZone $zone, $dst, $utcOffset, $timestamp)
    {
        $this->dateTimeZone = $zone;
        $this->dst = (bool) $dst;
        $this->utcOffset = $utcOffset ? (int) $utcOffset : null;
        $this->timestamp = $timestamp ? (int) $timestamp : null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->dateTimeZone->getName();
    }

    /**
     * @return DateTimeZone
     */
    public function getDateTimeZone()
    {
        return $this->dateTimeZone;
    }

    /**
     * @return int|null
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getUtcOffset()
    {
        return $this->utcOffset;
    }

    /**
     * @return bool
     */
    public function isDst()
    {
        return $this->dst;
    }
}
