<?php

namespace Teazee\Spec\Model;

use Teazee\Model\ZoneInfoFactory;
use Teazee\Model\ZoneInfo;
use DateTimeZone;

describe(ZoneInfoFactory::class, function () {
    before (function () {
        $this->factory = new ZoneInfoFactory();
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

        it ('creates a ' . ZoneInfo::class, function () {
            expect($this->tz)->toBeAnInstanceOf(ZoneInfo::class);
        });

        it ('is a ' . DateTimeZone::class, function () {
            expect($this->tz)->toBeAnInstanceOf(DateTimeZone::class);
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

        it ('has no DateTime', function () {
            expect($this->tz->getDateTime())->toBeNull();
        });

        it ('knows not the country code', function () {
           expect($this->tz->getCountry())->toBe('??');
        });

        it ('can override the country code', function () {
            $data = $this->values + ['country' => 'UK'];

            $this->tz = $this->factory->create($data);

            expect($this->tz->getCountry())->toBe('UK');
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

            it ('->getDateTime()', function () {
                expect($this->tz->getDateTime()->format('Y-m-d H:i:s'))->toBe('2015-10-31 08:00:00');
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

            it ('->getDateTime()', function () {
                expect($this->tz->getDateTime()->format('Y-m-d H:i:s'))->toBe('2015-11-02 08:00:00');
            });
        });
    });
});
