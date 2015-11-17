<?php

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
