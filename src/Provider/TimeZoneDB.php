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
     * @param string $apiKey
     * @param HttpClient $client
     */
    public function __construct($apiKey, HttpClient $client = null)
    {
        parent::__construct($client);

        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'timezonedb';
    }

    /**
     * @param string|float $lat
     * @param string|float $lng
     * @param int $timestamp
     * @return \Teazee\Model\TimeZone
     */
    public function find($lat, $lng, $timestamp = null)
    {
        $query    = $this->buildQuery($lat, $lng, $timestamp);
        $response = $this->getResponse($query);
        $data     = json_decode($response->getBody()->getContents());

        if (static::FAIL === $data->status) {
            throw new \RuntimeException($data->message);
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
     * @param $lat
     * @param $lng
     * @param null $timestamp
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

        return static::ENDPOINT . '?' . http_build_query($params);
    }
}
