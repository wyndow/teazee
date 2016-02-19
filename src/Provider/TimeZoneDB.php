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
use Teazee\Model\ZoneInfo;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
class TimeZoneDB extends AbstractHttpProvider
{
    const ENDPOINT = 'https://api.timezonedb.com/';
    const FAIL = 'FAIL';
    const SUCCESS = 'OK';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * TimeZoneDB Constructor.
     *
     * @param string         $apiKey
     * @param HttpClient     $client
     * @param MessageFactory $messageFactory
     */
    public function __construct($apiKey, HttpClient $client = null, MessageFactory $messageFactory = null)
    {
        parent::__construct($client, $messageFactory);

        $this->apiKey = $apiKey;
    }

    /**
     * Returns the name of this Provider.
     *
     * @return string
     */
    public function getName()
    {
        return 'timezonedb';
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
        $query = $this->buildQuery($lat, $lng, $timestamp);
        $response = $this->getResponse($query);
        $data = json_decode($response->getBody()->getContents());

        if (static::FAIL === $data->status) {
            throw new RuntimeException($data->message);
        }

        return $this->returnResult(array_merge($this->getDefaults(), [
            'id'        => $data->zoneName,
            'dst'       => (bool) $data->dst,
            'timestamp' => $data->timestamp - $data->gmtOffset,
            'utcOffset' => $data->gmtOffset,
            'country'   => $data->countryCode,
        ]));
    }

    /**
     * Returns the URI for the specified location and timestamp.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int          $timestamp UNIX timestamp used to determine Daylight Savings Time.
     *
     * @return string
     */
    private function buildQuery($lat, $lng, $timestamp = null)
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

        return static::ENDPOINT.'?'.http_build_query($params);
    }
}
