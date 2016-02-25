<?php

namespace Teazee\Spec\Provider;

use DateTimeImmutable;
use DateTimeZone;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as HttpAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use RuntimeException;
use Teazee\Provider\GoogleMaps;
use Teazee\ZoneInfo;
use VCR\VCR;

describe(GoogleMaps::class, function () {

    before(function () {
        VCR::insertCassette('google_maps');
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
        expect($this->teazee->getName())->toBe('google_maps');
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

    context('when no timestamp given', function () {
        before(function () {
            VCR::turnOff();
        });

        beforeEach(function () {
            $this->now = (new DateTimeImmutable(null, new DateTimeZone('UTC')))->getTimestamp();
            $this->time = $this->teazee->find($this->lat, $this->lng)->getTimestamp();
        });

        it('uses the default timestamp', function () {
            // Note: this _could_ fail if response times are especially bad, but it's google,
            // so we'll take our chances.
            expect($this->time)->toBeGreaterThan($this->now - 1);
            expect($this->time)->toBeLessThan($this->now + 3);
        });

        after(function () {
            VCR::turnOn();
            VCR::insertCassette('google_maps');
        });
    });

    after(function () {
        VCR::eject();
    });
});
