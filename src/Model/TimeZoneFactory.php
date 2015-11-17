<?php

namespace Teazee\Model;

use DateTimeZone;

class TimeZoneFactory
{
    public static function create($data)
    {
        $dtz = new DateTimeZone($data['id']);

        return new TimeZone($dtz, $data['dst'], $data['utcOffset'], $data['timestamp']);
    }
}
