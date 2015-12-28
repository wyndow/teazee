<?php

namespace Teazee\Spec\Provider;

use Http\Client\HttpClient;
use RuntimeException;
use Teazee\Model\ZoneInfo;
use Teazee\Provider\TimeZoneDB;
use VCR\VCR;

describe(TimeZoneDB::class, function () {

    before(function () {
        VCR::insertCassette('timezonedb');
        $this->lat = 40.1146914;
        $this->lng = -88.3121289;
    });

    beforeEach(function () {
        $this->teazee = new TimeZoneDB('TVDB_TEAZEE_KEY');
    });

    it('->getName()', function () {
        expect($this->teazee->getName())->toBe('timezonedb');
    });

    it('->getClient()', function () {
        expect($this->teazee->getClient())->toBeAnInstanceOf(HttpClient::class);
    });

    context('with coordinates', function () {

        beforeEach(function () {
           $this->tz = $this->teazee->find($this->lat, $this->lng);
        });

        it('->find() returns a TimeZone', function () {
            expect($this->tz)->toBeAnInstanceOf(ZoneInfo::class);
        });

        it('->find() has a timestamp in utc', function () {
            expect($this->tz->getTimestamp())->toBe(1447744964);
        });
    });

    context('with coordinates and timestamp', function () {
        it('->find() can take an optional timestamp', function () {
            $this->tz = $this->teazee->find($this->lat, $this->lng, 1446296400);

            expect($this->tz->isDst())->toBe(true);
        });
    });

    context('with invalid parameters', function () {

        it('fails without an API key', function () {
            $expected = function () {
                $this->teazee = new TimeZoneDB('');
                $this->teazee->find($this->lat, $this->lng);
            };

            expect($expected)
                ->toThrow(new RuntimeException('Parameter "key" (API Key) is not provided.'));
        });

        it('fails with a bad API key', function () {
            $expected = function () {
                $this->teazee = new TimeZoneDB('TEAZEE_BAD_KEY');
                $this->teazee->find($this->lat, $this->lng);
            };

            expect($expected)
                ->toThrow(new RuntimeException('Invalid API key.'));
        });

        it('fails on invalid coordinates', function () {
            $expected = function () {
                $this->teazee->find('foo', 'bar');
            };

            expect($expected)->toThrow(new RuntimeException('Invalid latitude value.'));
        });
    });

    after(function () {
        VCR::eject();
    });
});
