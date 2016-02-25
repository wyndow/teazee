<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Provider;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use RuntimeException;
use Teazee\ZoneInfo;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
class TimeZoneDB extends AbstractHttpProvider
{
    const ENDPOINT = 'https://api.timezonedb.com/';
    const VIP_ENDPOINT = 'https://vip.timezonedb.com/';
    const FAIL = 'FAIL';
    const SUCCESS = 'OK';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $host;

    /**
     * TimeZoneDB Constructor.
     *
     * @param string         $apiKey
     * @param bool           $premium
     * @param HttpClient     $client
     * @param MessageFactory $messageFactory
     */
    public function __construct(
        $apiKey,
        $premium = false,
        HttpClient $client = null,
        MessageFactory $messageFactory = null
    ) {
        parent::__construct($client, $messageFactory);

        $this->apiKey = $apiKey;
        $this->host = (bool) $premium ? static::VIP_ENDPOINT : static::ENDPOINT;
    }

    /**
     * Returns the name of this Provider.
     *
     * @return string
     */
    public function getName()
    {
        return 'timezone_db';
    }

    /**
     * Returns ZoneInfo for the specified location and timestamp.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int          $timestamp UNIX timestamp used to determine Daylight Savings Time.
     *
     * @return ZoneInfo
     */
    public function find($lat, $lng, $timestamp = null)
    {
        $result = $this->getResult($lat, $lng, $timestamp);

        if (static::FAIL === $result->status) {
            throw new RuntimeException($result->message);
        }

        return new ZoneInfo($result->zoneName, $result->timestamp - $result->gmtOffset);
    }

    /**
     * Returns the URI for the specified location and timestamp.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int|null     $timestamp Seconds since Jan 1, 1970 UTC.
     *
     * @return string
     */
    protected function buildUri($lat, $lng, $timestamp = null)
    {
        $params = [
            'key'    => $this->apiKey,
            'lat'    => $lat,
            'lng'    => $lng,
            'time'   => $timestamp,
            'format' => 'json',
        ];

        // Remove null values.
        $params = array_filter($params);

        return $this->host.'?'.http_build_query($params);
    }
}
