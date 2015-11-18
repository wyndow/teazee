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
     * @var \DateTimeImmutable
     */
    private $dateTime;

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
        $this->dst          = $dst ? (bool) $dst : null;
        $this->utcOffset    = $utcOffset ? (int) $utcOffset : null;
        $this->timestamp    = $timestamp ? (int) $timestamp : null;

        if ($this->timestamp) {
            $this->dateTime = (new \DateTimeImmutable('@'.$this->timestamp))->setTimezone($zone);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->dateTimeZone->getName();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTime()
    {
        return $this->dateTime;
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
