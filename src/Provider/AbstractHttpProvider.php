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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Teazee\Exception\ServiceMissingException;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
abstract class AbstractHttpProvider extends AbstractProvider
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * AbstractHttpProvider Constructor.
     *
     * @param HttpClient     $client         HttpClient makes HTTP requests.
     * @param MessageFactory $messageFactory MessageFactory creates Request objects.
     */
    public function __construct(HttpClient $client = null, MessageFactory $messageFactory = null)
    {
        if (null === $client && !class_exists('Http\Discovery\HttpClientDiscovery')) {
            throw ServiceMissingException::noHttpClient();
        }

        $this->client = $client ?: \Http\Discovery\HttpClientDiscovery::find();

        if (null === $messageFactory && !class_exists('Http\Discovery\MessageFactoryDiscovery')) {
            throw ServiceMissingException::noMessageFactory();
        }

        $this->messageFactory = $messageFactory ?: \Http\Discovery\MessageFactoryDiscovery::find();
    }

    /**
     * Returns a ResponseInterface for the given URI/method.
     *
     * @param string|UriInterface $uri    Request URI.
     * @param string              $method HTTP method (Defaults to 'GET').
     *
     * @return ResponseInterface
     */
    protected function getResponse($uri, $method = 'GET')
    {
        $request = $this->messageFactory->createRequest($method, $uri);

        return $this->client->sendRequest($request);
    }

    /**
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int|null     $timestamp Seconds since Jan 1, 1970 UTC.
     *
     * @throws RuntimeException When the response body cannot be decoded.
     *
     * @return object
     */
    protected function getResult($lat, $lng, $timestamp = null)
    {
        $query = $this->buildUri($lat, $lng, $timestamp);
        $response = $this->getResponse($query);

        $data = json_decode($response->getBody()->getContents());

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException((string)json_last_error_msg());
        }

        return $data;
    }

    /**
     * Returns the URI for the specified location and timestamp.
     *
     * @param string|float $lat       Coordinate latitude.
     * @param string|float $lng       Coordinate longitude.
     * @param int|null     $timestamp Seconds since Jan 1, 1970 UTC.
     *
     * @return string|UriInterface
     */
    abstract protected function buildUri($lat, $lng, $timestamp = null);
}
