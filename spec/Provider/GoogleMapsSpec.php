<?php

namespace Teazee\Spec\Provider;

use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as HttpAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use RuntimeException;
use Teazee\Model\ZoneInfo;
use Teazee\Provider\GoogleMaps;
use VCR\VCR;

describe(GoogleMaps::class, function () {

    before(function () {
        VCR::insertCassette('googlemaps');
        $this->lat = 40.1146914;
        $this->lng = -88.3121289;
    });

    beforeEach(function () {
        $this->teazee = new GoogleMaps(
            'TZDB_GOOGLEMAPS_KEY',
            new HttpAdapter(new Client()),
            new GuzzleMessageFactory()
        );
    });

    it('->getName()', function () {
        expect($this->teazee->getName())->toBe('googlemaps');
    });

    context('without discovery', function () {
        it('fails without an HttpClient', function () {
            expect(function () { new GoogleMaps('SOME_API_KEY'); })->toThrow();
        });

        it('fails without a MessageFactory', function () {
            expect(function () { new GoogleMaps('SOME_API_KEY', new HttpAdapter(new Client())); })->toThrow();
        });
    });

    context('with coordinates and timestamp', function () {

        beforeEach(function () {
            $this->time = 1455926050;
            $this->tz = $this->teazee->find($this->lat, $this->lng, $this->time);
        });

        it('->find() returns a TimeZone', function () {
            expect($this->tz)->toBeAnInstanceOf(ZoneInfo::class);
        });

        it('->find() returns with the given timestamp', function () {
            expect($this->tz->getTimestamp())->toBe($this->time);
        });
    });

    context('with invalid parameters', function () {

        it('fails with an invalid API key', function () {
            $expected = function () {
                $this->teazee = new GoogleMaps(
                    'TZDB_BAD_KEY',
                    new HttpAdapter(new Client()),
                    new GuzzleMessageFactory()
                );
                $this->teazee->find($this->lat, $this->lng, 1455926050);
            };

            expect($expected)
                ->toThrow(new RuntimeException('The provided API key is invalid.'));
        });
    });

    after(function () {
        VCR::eject();
    });
});
