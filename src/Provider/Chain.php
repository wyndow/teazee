<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Provider;

use RuntimeException;
use Teazee\Exception\ChainNoResult;
use Teazee\ZoneInfo;

/**
 * Query multiple providers until a result is found.
 *
 * @author Michael Crumm <mike@crumm.net>
 */
class Chain implements Provider
{
    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * Chain constructor.
     *
     * @param array $providers
     */
    public function __construct(array $providers = [])
    {
        $this->providers = $providers;
    }

    /**
     * Returns the name of this provider.
     *
     * @return string
     */
    public function getName()
    {
        return 'chain';
    }

    /**
     * Finds a TimeZone for a location on the surface of the earth.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int|null     $timestamp Seconds since Jan 1, 1970 UTC.
     *
     * @return ZoneInfo
     */
    public function find($lat, $lng, $timestamp = null)
    {
        $exceptions = [];

        /** @var Provider $provider */
        foreach ($this->providers as $provider) {
            try {
                return $provider->find($lat, $lng, $timestamp);
            } catch (RuntimeException $ex) {
                $exceptions[] = $ex;
            }
        }

        throw new ChainNoResult(
            sprintf('No provider could find %f,%f%s', $lat, $lng, $timestamp ? ' @'.$timestamp : ''),
            $exceptions
        );
    }
}
