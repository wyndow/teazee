<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee\Provider;

use Teazee\Model\TimeZone;
use Teazee\Model\TimeZoneFactory;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
abstract class AbstractProvider implements Provider
{
    /**
     * @var TimeZoneFactory
     */
    private $factory;

    /**
     * AbstractProvider Constructor.
     */
    public function __construct()
    {
        $this->factory = new TimeZoneFactory();
    }

    /**
     * Returns the default values for creating a TimeZone.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'dst' => null,
            'id' => null,
            'timestamp' => null,
            'utcOffset' => null,
        ];
    }

    /**
     * Creates a TimeZone from the given parameters via the TimeZoneFactory.
     *
     * @param array $data
     *
     * @return TimeZone
     */
    protected function returnResult(array $data)
    {
        return $this->factory->create($data);
    }
}
