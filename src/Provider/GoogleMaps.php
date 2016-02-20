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
class GoogleMaps extends AbstractHttpProvider
{
    const ENDPOINT = 'https://maps.googleapis.com/maps/api/timezone/json';

    /**
     * @var array
     */
    private $errorStatus = [
        'INVALID_REQUEST',
        'OVER_QUERY_LIMIT',
        'REQUEST_DENIED',
        'UNKNOWN_ERROR',
        'ZERO_RESULTS',
    ];

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Google constructor.
     *
     * @param string         $apiKey
     * @param HttpClient     $client
     * @param MessageFactory $messageFactory
     */
    public function __construct($apiKey = null, HttpClient $client = null, MessageFactory $messageFactory = null)
    {
        $this->apiKey = $apiKey;

        parent::__construct($client, $messageFactory);
    }

    /**
     * Returns the name of this provider.
     *
     * @return string
     */
    public function getName()
    {
        return 'googlemaps';
    }

    /**
     * Returns ZoneInfo for the specified location and timestamp.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int          $timestamp UNIX timestamp used to determine Daylight Savings Time.
     *
     * @throws RuntimeException
     *
     * @return ZoneInfo
     */
    public function find($lat, $lng, $timestamp = null)
    {
        $timestamp = $timestamp ?: $this->getCurrentTimestamp();
        $query = $this->buildQuery($lat, $lng, $timestamp);
        $response = $this->getResponse($query);
        $data = json_decode($response->getBody()->getContents());

        if (in_array($data->status, $this->errorStatus)) {
            $message = isset($data->errorMessage) ? $data->errorMessage : $data->status;
            throw new RuntimeException($message);
        }

        return $this->returnResult(array_merge($this->getDefaults(), [
            'id'        => $data->timeZoneId,
            'dst'       => $data->dstOffset !== 0,
            'timestamp' => $timestamp,
            'utcOffset' => $data->rawOffset,
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
    private function buildQuery($lat, $lng, $timestamp)
    {
        $params = [
            'key'       => $this->apiKey,
            'location'  => implode(',', [$lat, $lng]),
            'timestamp' => $timestamp,
        ];

        // Remove null values.
        $params = array_filter($params);

        return static::ENDPOINT.'?'.http_build_query($params);
    }
}
