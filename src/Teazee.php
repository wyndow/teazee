<?php

namespace Teazee;

interface Teazee
{
    const VERSION = '0.1.0-dev';

    public function find($lat, $lng, $timestamp);
}
