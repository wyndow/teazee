<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Teazee\Provider;

use Teazee\Model\ZoneInfo;
use Teazee\Model\ZoneInfoFactory;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
abstract class AbstractProvider implements Provider
{
    /**
     * @var ZoneInfoFactory
     */
    private $factory;

    /**
     * AbstractProvider Constructor.
     */
    public function __construct()
    {
        $this->factory = new ZoneInfoFactory();
    }

    /**
     * Returns the default values for creating ZoneInfo.
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
     * Creates ZoneInfo from the given parameters via the ZoneInfoFactory.
     *
     * @param array $data
     *
     * @return ZoneInfo
     */
    protected function returnResult(array $data)
    {
        return $this->factory->create($data);
    }
}
