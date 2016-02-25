<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Value Object for a DateTimeZone at a specific moment in time.
 *
 * @author Michael Crumm <mike@crumm.net>
 */
class ZoneInfo extends DateTimeZone
{
    /**
     * @var DateTimeImmutable
     */
    private $dateTime;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var string
     */
    private $country;

    /**
     * @var array
     */
    private $info;

    /**
     * ZoneInfo constructor.
     *
     * @param string $id        IANA Timezone identifier.
     * @param int    $timestamp Seconds since January 1, 1970 UTC.
     */
    public function __construct($id, $timestamp)
    {
        parent::__construct($id);

        $this->timestamp = (int) $timestamp;
        $this->dateTime = new DateTimeImmutable('@'.$this->timestamp, $this);
        $this->country = $this->getLocation()['country_code'];
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->loadInfo()['abbr'];
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return int
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
        return $this->loadInfo()['offset'];
    }

    /**
     * @return bool
     */
    public function isDst()
    {
        return $this->loadInfo()['isdst'];
    }

    /**
     * Lazily loads the transition info.
     *
     * @return array
     */
    private function loadInfo()
    {
        if (null === $this->info) {
            $this->info = $this->getCurrentTransition($this->timestamp);
        }

        return $this->info;
    }

    /**
     * @param int $timestamp
     *
     * @return array
     */
    private function getCurrentTransition($timestamp)
    {
        $transitions = $this->getTransitions($timestamp, $timestamp);
        if (false === $transitions) {
            $transitions = [['abbr' => null, 'isdst' => null, 'offset' => null]];
        }

        return $transitions[0];
    }
}
