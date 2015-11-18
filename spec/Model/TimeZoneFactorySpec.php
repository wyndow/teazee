<?php

namespace Teazee\Spec\Model;

use Teazee\Model\TimeZoneFactory;
use Teazee\Model\TimeZone;
use DateTimeZone;

describe(TimeZoneFactory::class, function () {
    before (function () {
        $this->factory = new TimeZoneFactory();
    });

    context ('with defaults', function () {
        before (function () {
            $this->defaults = [
                'id'        => null,
                'dst'       => null,
                'utcOffset' => null,
                'timestamp' => null,
            ];

            $this->closure = function () {
                $this->tz = $this->factory->create($this->defaults);
            };
        });

        it ('fails without a valid IANA TimeZone ID', function () {
            expect($this->closure)->toThrow();
        });
    });

    context ('with minimal values', function () {
        before (function () {
            $this->values = [
                'id'        => 'UTC',
                'dst'       => null,
                'utcOffset' => null,
                'timestamp' => null
            ];
        });

        beforeEach (function () {
            $this->tz = $this->factory->create($this->values);
        });

        it ('creates a ' . TimeZone::class, function () {
            expect($this->tz)->toBeAnInstanceOf(TimeZone::class);
        });

        it ('->getDateTimeZone()', function () {
            expect($this->tz->getDateTimeZone())->toBeAnInstanceOf(DateTimeZone::class);
        });

        it ('->getName()', function () {
            expect($this->tz->getName())->toBe('UTC');
        });

        it ('is dst falsey', function () {
            expect($this->tz->isDst())->toBeFalsy();
        });

        it ('has no utc offset', function () {
            expect($this->tz->getUtcOffset())->toBeNull();
        });

        it ('has no timestamp', function () {
            expect($this->tz->getTimestamp())->toBeNull();
        });
    });

    context ('with US Pacific', function () {
        before (function () {
            $this->values = [
                'id' => 'America/Los_Angeles'
            ];
        });

        context ('when DST', function () {
            before (function () {
                $this->values = $this->values + [
                    'dst'       => 1,
                    'utcOffset' => -25200,
                    'timestamp' => 1446303600
                ];
            });

            beforeEach (function () {
                $this->tz = $this->factory->create($this->values);
            });

            it ('->isDst()', function () {
                expect($this->tz->isDst())->toBeTruthy();
            });

            it ('->getTimestamp()', function () {
                expect($this->tz->getTimestamp())->toBe(1446303600);
            });
        });

        context ('when not DST', function () {
            before (function () {
                $this->values = $this->values + [
                    'dst'       => 0,
                    'utcOffset' => -28800,
                    'timestamp' => 1446480000
                ];
            });

            beforeEach (function () {
                $this->tz = $this->factory->create($this->values);
            });

            it ('->isDst()', function () {
                expect($this->tz->isDst())->toBeFalsy();
            });

            it ('->getName()', function () {
                expect($this->tz->getName())->toBe('America/Los_Angeles');
            });

            it ('->getTimestamp()', function () {
                expect($this->tz->getTimestamp())->toBe(1446480000);
            });
        });
    });
});
