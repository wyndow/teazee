<?php

namespace Teazee\Provider;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;

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
     * @param HttpClient $client
     * @param MessageFactory $messageFactory
     */
    public function __construct(HttpClient $client = null, MessageFactory $messageFactory = null)
    {
        parent::__construct();

        $this->client = $client ?: HttpClientDiscovery::find();
        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * @return HttpClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string|\Psr\Http\Message\UriInterface $uri
     * @param string $method
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getResponse($uri, $method = 'GET')
    {
        $request = $this->messageFactory->createRequest($method, $uri);

        return $this->client->sendRequest($request);
    }
}
