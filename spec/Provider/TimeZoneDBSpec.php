<?php

namespace Teazee\Spec\Provider;

use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as ClientAdapter;
use Http\Client\HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use RuntimeException;
use Teazee\Provider\TimeZoneDB;
use Teazee\ZoneInfo;
use VCR\VCR;

describe(TimeZoneDB::class, function () {

    before(function () {
        VCR::insertCassette('timezonedb');
        $this->lat = 40.1146914;
        $this->lng = -88.3121289;
    });

    beforeEach(function () {
        $this->teazee = new TimeZoneDB(
            'TVDB_TEAZEE_KEY',
            false,
            new ClientAdapter(new Client()),
            new GuzzleMessageFactory()
        );
    });

    it('->getName()', function () {
        expect($this->teazee->getName())->toBe('timezonedb');
    });

    it('->getClient()', function () {
        expect($this->teazee->getClient())->toBeAnInstanceOf(HttpClient::class);
    });

    context('without discovery', function () {
        it('fails without an HttpClient', function () {
            expect(function () { new TimeZoneDB('SOME_API_KEY'); })->toThrow();
        });

        it('fails without a MessageFactory', function () {
            expect(function () { new TimeZoneDB('SOME_API_KEY', false, new ClientAdapter(new Client())); })->toThrow();
        });
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
        before(function () {
            $this->ts = 1446296400;
        });

        beforeEach(function () {
            $this->tz = $this->teazee->find($this->lat, $this->lng, $this->ts);
        });

        it('->find() can take an optional timestamp', function () {
            expect($this->tz->isDst())->toBe(true);
        });

        it(ZoneInfo::class.' contains the given timestamp', function () {
            expect($this->tz->getTimestamp())->toBe($this->ts);
        });
    });

    context('with invalid parameters', function () {

        it('fails without an API key', function () {
            $expected = function () {
                $this->teazee = new TimeZoneDB(
                    '',
                    false,
                    new ClientAdapter(new Client()),
                    new GuzzleMessageFactory()
                );
                $this->teazee->find($this->lat, $this->lng);
            };

            expect($expected)
                ->toThrow(new RuntimeException('Parameter "key" (API Key) is not provided.'));
        });

        it('fails with a bad API key', function () {
            $expected = function () {
                $this->teazee = new TimeZoneDB(
                    'TEAZEE_BAD_KEY',
                    false,
                    new ClientAdapter(new Client()),
                    new GuzzleMessageFactory()
                );
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
