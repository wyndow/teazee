<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee\Model;

use DateTimeZone;

class TimeZoneFactory
{
    /**
     * @param array $data
     * @return TimeZone
     * @throws \Exception When given an invalid time zone identifier.
     */
    public function create($data)
    {
        return new TimeZone(
            new DateTimeZone($this->getValue($data, 'id')),
            $this->getValue($data, 'dst'),
            $this->getValue($data, 'utcOffset'),
            $this->getValue($data, 'timestamp'),
            $this->getValue($data, 'country')
        );
    }

    /**
     * @param  array  $data
     * @param  string $key
     * @return mixed
     */
    private function getValue(array $data, $key)
    {
        return $this->valueOrNull(\igorw\get_in($data, [$key]));
    }

    /**
     * @param mixed $str
     * @return string|null
     */
    private function valueOrNull($str)
    {
        return empty ($str) ? null : $str;
    }
}
