<?php

namespace Teazee\Spec;

use Http\Client\HttpClient;
use Teazee\Model\TimeZone;
use Teazee\Provider\TimeZoneDB;
use VCR\VCR;
use DateTimeZone;
use RuntimeException;

describe (TimeZoneDB::class, function () {

    before (function() {
        VCR::insertCassette('timezonedb');
        $this->lat = 40.1146914;
        $this->lng = -88.3121289;
    });

    after (function() {
        VCR::eject();
    });

    beforeEach (function() {
        $this->teazee = new TimeZoneDB('TVDB_TEAZEE_KEY');
    });

    it ('->getName()', function () {
        expect($this->teazee->getName())->toBe('timezonedb');
    });

    it ('->getClient()', function () {
        expect($this->teazee->getClient())->toBeAnInstanceOf(HttpClient::class);
    });

    it ('->find() returns a TimeZone', function () {
        $tz = $this->teazee->find($this->lat, $this->lng);

        expect ($tz)->toBeAnInstanceOf(TimeZone::class);
    });

    it ('->find() has a DateTimeZone', function () {
        $tz = $this->teazee->find($this->lat, $this->lng);

        expect ($tz->getDateTimeZone())->toBeAnInstanceOf(DateTimeZone::class);
    });

    it ('->find() has a name', function () {
        $tz = $this->teazee->find($this->lat, $this->lng);

        expect ($tz->getName())->toBe('America/Chicago');
    });

    it ('->find() has a timestamp', function () {
        $tz = $this->teazee->find($this->lat, $this->lng);

        expect ($tz->getTimestamp())->toBeAn('int');
    });

    it ('->find() has a utc offset', function () {
        $tz = $this->teazee->find($this->lat, $this->lng);

        expect ($tz->getUtcOffset())->toBeAn('int');
    });

    it ('->find() can check for daylight savings time', function () {
        $tz = $this->teazee->find($this->lat, $this->lng);

        expect ($tz->isDst())->toBe(false);
    });

    it ('->find() can take an optional timestamp', function () {
        $tz = $this->teazee->find($this->lat, $this->lng, 1446296400);

        expect ($tz->isDst())->toBe(true);
    });

    it ('fails without an API key', function () {
        $expected = function () {
            $this->teazee = new TimeZoneDB('');
            $this->teazee->find($this->lat, $this->lng);
        };

        expect ($expected)
            ->toThrow(new RuntimeException('Parameter "key" (API Key) is not provided.'));
    });

    it ('fails with a bad API key', function () {
        $expected = function () {
            $this->teazee = new TimeZoneDB('TEAZEE_BAD_KEY');
            $this->teazee->find($this->lat, $this->lng);
        };

        expect ($expected)
            ->toThrow(new RuntimeException('Invalid API key.'));
    });

    it ('fails on invalid params', function () {
        $expected = function () {
            $this->teazee->find('foo', 'bar');
        };

        expect ($expected)->toThrow(new RuntimeException('Invalid latitude value.'));
    });
});
