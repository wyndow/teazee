<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Model;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
final class ZoneInfoFactory
{
    /**
     * Creates a ZoneInfo object from an array of parameters.
     *
     * @param array $data
     *
     * @throws \Exception When given an invalid time zone identifier.
     *
     * @return ZoneInfo
     */
    public function create($data)
    {
        return new ZoneInfo(
            $this->getValue($data, 'id'),
            $this->getValue($data, 'dst'),
            $this->getValue($data, 'utcOffset'),
            $this->getValue($data, 'timestamp'),
            $this->getValue($data, 'country')
        );
    }

    /**
     * @param array  $data
     * @param string $key
     *
     * @return mixed
     */
    private function getValue(array $data, $key)
    {
        return $this->valueOrNull(\igorw\get_in($data, [$key]));
    }

    /**
     * @param mixed $str
     *
     * @return mixed
     */
    private function valueOrNull($str)
    {
        return empty($str) ? null : $str;
    }
}
