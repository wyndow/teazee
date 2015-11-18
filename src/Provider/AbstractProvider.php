<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee\Provider;

use Teazee\Model\TimeZoneFactory;

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
     * @param $data
     * @return \Teazee\Model\TimeZone
     */
    protected function returnResult($data)
    {
        return $this->factory->create($data);
    }
}
