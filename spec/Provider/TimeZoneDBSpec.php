<?php

namespace Teazee\Spec\Provider;

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

    beforeEach (function() {
        $this->teazee = new TimeZoneDB('TVDB_TEAZEE_KEY');
    });

    it ('->getName()', function () {
        expect($this->teazee->getName())->toBe('timezonedb');
    });

    it ('->getClient()', function () {
        expect($this->teazee->getClient())->toBeAnInstanceOf(HttpClient::class);
    });

    context('with coordinates', function () {

        beforeEach (function () {
           $this->tz = $this->teazee->find($this->lat, $this->lng);
        });

        it ('->find() returns a TimeZone', function () {
            expect ($this->tz)->toBeAnInstanceOf(TimeZone::class);
        });

        it ('->find() has a DateTimeZone', function () {
            expect ($this->tz->getDateTimeZone())->toBeAnInstanceOf(DateTimeZone::class);
        });

        it ('->find() has a name', function () {
            expect ($this->tz->getName())->toBe('America/Chicago');
        });

        it ('->find() has a timestamp', function () {
            expect ($this->tz->getTimestamp())->toBeAn('int');
        });

        it ('->find() has a utc offset', function () {
            expect ($this->tz->getUtcOffset())->toBeAn('int');
        });

        it ('->find() has a timestamp in utc', function () {
            expect ($this->tz->getTimestamp())->toBe(1447744964);
        });

        it ('->find() can check for daylight savings time', function () {
            expect ($this->tz->isDst())->toBeFalsy();
        });
    });

    context ('with coordinates and timestamp', function () {
        it ('->find() can take an optional timestamp', function () {
            $this->tz = $this->teazee->find($this->lat, $this->lng, 1446296400);

            expect ($this->tz->isDst())->toBe(true);
        });
    });

    context ('with invalid parameters', function () {

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

        it ('fails on invalid coordinates', function () {
            $expected = function () {
                $this->teazee->find('foo', 'bar');
            };

            expect ($expected)->toThrow(new RuntimeException('Invalid latitude value.'));
        });
    });

    after (function() {
        VCR::eject();
    });
});
