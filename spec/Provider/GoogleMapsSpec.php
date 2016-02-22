<?php

namespace Teazee\Spec\Provider;

use DateTimeImmutable;
use DateTimeZone;
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
            null,
            new HttpAdapter(new Client()),
            new GuzzleMessageFactory()
        );
    });

    it('->getName()', function () {
        expect($this->teazee->getName())->toBe('googlemaps');
    });

    context('without discovery', function () {
        it('fails without an HttpClient', function () {
            expect(function () { new GoogleMaps(); })->toThrow();
        });

        it('fails without a MessageFactory', function () {
            expect(function () { new GoogleMaps(null, new HttpAdapter(new Client())); })->toThrow();
        });
    });

    context('CST', function () {
        before(function () {
            $this->date = new DateTimeImmutable('2016-02-21', new DateTimeZone('UTC'));
            $this->zone = $this->teazee->find($this->lat, $this->lng, $this->date->getTimestamp());
        });

        it('->find() returns a TimeZone', function () {
            expect($this->zone)->toBeAnInstanceOf(ZoneInfo::class);
        });

        it('->find() returns with the given timestamp', function () {
            expect($this->zone->getTimestamp())->toBe($this->date->getTimestamp());
        });

        it('->find() returns utc offset', function () {
            expect($this->zone->getUtcOffset())->toBe(-21600);
        });

        it('->find() is dst', function () {
            expect($this->zone->isDst())->toBe(false);
        });
    });

    context('CDT', function () {
        before(function () {
            $this->date = new DateTimeImmutable('2016-04-21', new DateTimeZone('UTC'));
            $this->zone = $this->teazee->find($this->lat, $this->lng, $this->date->getTimestamp());
        });

        it('->find() returns a TimeZone', function () {
            expect($this->zone)->toBeAnInstanceOf(ZoneInfo::class);
        });

        it('->find() returns with the given timestamp', function () {
            expect($this->zone->getTimestamp())->toBe($this->date->getTimestamp());
        });

        it('->find() returns adjusted utc offset', function () {
            expect($this->zone->getUtcOffset())->toBe(-18000);
        });

        it('->find() is dst', function () {
            expect($this->zone->isDst())->toBe(true);
        });
    });

    context('with invalid parameters', function () {

        it('fails with an invalid API key', function () {
            $expected = function () {
                (new GoogleMaps(
                    'TEAZEE_BAD_KEY',
                    new HttpAdapter(new Client()),
                    new GuzzleMessageFactory()
                ))
                ->find($this->lat, $this->lng, 1455926050);
            };

            expect($expected)
                ->toThrow(new RuntimeException('The provided API key is invalid.'));
        });
    });

    after(function () {
        VCR::eject();
    });
});
