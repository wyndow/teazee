<?php

namespace Teazee\Spec;

use DateTimeImmutable;
use DateTimeZone;
use Kahlan\Plugin\Stub;
use Teazee\ZoneInfo;

describe(ZoneInfo::class, function () {
    before(function () {
        $this->utc = new DateTimeZone('UTC');
        $this->jan1970 = 0;
        $this->feb2016 = (new DateTimeImmutable('2016-02-21', $this->utc))->getTimestamp();
        $this->apr2016 = (new DateTimeImmutable('2016-04-21', $this->utc))->getTimestamp();
    });

    it('requires a timezone id', function () {
        expect(function () { new ZoneInfo(); })->toThrow();
    });

    it('requires a timestamp', function () {
        expect(function () { new ZoneInfo('UTC'); })->toThrow();
    });

    it('is a '.DateTimeZone::class, function () {
        $zone = new ZoneInfo('UTC', time());
        expect($zone)->toBeAnInstanceOf(DateTimeZone::class);
    });

    it('will set info null on error', function () {
        $dtz = Stub::create([
            'extends' => ZoneInfo::class,
            'params'  => ['UTC', time()],
            'layer'   => true,
        ]);
        Stub::on($dtz)->method('getTransitions')->andReturn(false);
        expect($dtz->isDst())->toBeNull();
    });

    context('America', function () {
        context('Los Angeles', function () {
            before(function () {
                $this->id = 'America/Los_Angeles';
                $this->zone = new ZoneInfo($this->id, $this->feb2016);
                $this->dstZone = new ZoneInfo($this->id, $this->apr2016);
            });

            it('->getAbbreviation()', function () {
                expect($this->zone->getAbbreviation())->toBe('PST');
                expect($this->dstZone->getAbbreviation())->toBe('PDT');
            });

            it('->getCountry()', function () {
                expect($this->zone->getCountry())->toBe('US');
            });

            it('->getDateTime()', function () {
                $dt = $this->zone->getDateTime();
                expect($dt)->toBeAnInstanceOf(DateTimeImmutable::class);
                expect($dt->getTimestamp())->toBe($this->feb2016);
            });

            it('->getTimestamp()', function () {
                expect($this->zone->getTimestamp())->toBe($this->feb2016);
            });

            it('->getUtcOffset()', function () {
                expect($this->zone->getUtcOffset())->toBe(-28800);
                expect($this->dstZone->getUtcOffset())->toBe(-25200);
            });

            it('->isDst()', function () {
                expect($this->zone->isDst())->toBe(false);
                expect($this->dstZone->isDst())->toBe(true);
            });
        });

        context('Chicago', function () {
            before(function () {
                $this->id = 'America/Chicago';
                $this->zone = new ZoneInfo($this->id, $this->feb2016);
                $this->dstZone = new ZoneInfo($this->id, $this->apr2016);
            });

            it('->getAbbreviation()', function () {
                expect($this->zone->getAbbreviation())->toBe('CST');
                expect($this->dstZone->getAbbreviation())->toBe('CDT');
            });

            it('->getCountry()', function () {
                expect($this->zone->getCountry())->toBe('US');
            });

            it('->getDateTime()', function () {
                $dt = $this->zone->getDateTime();
                expect($dt)->toBeAnInstanceOf(DateTimeImmutable::class);
                expect($dt->getTimestamp())->toBe($this->feb2016);
            });

            it('->getTimestamp()', function () {
                expect($this->zone->getTimestamp())->toBe($this->feb2016);
            });

            it('->getUtcOffset()', function () {
                expect($this->zone->getUtcOffset())->toBe(-21600);
                expect($this->dstZone->getUtcOffset())->toBe(-18000);
            });

            it('->isDst()', function () {
                expect($this->zone->isDst())->toBe(false);

                $dstZone = new ZoneInfo($this->id, $this->apr2016);
                expect($dstZone->isDst())->toBe(true);
            });
        });

        context('New York', function () {
            before(function () {
                $this->id = 'America/New_York';
                $this->zone = new ZoneInfo($this->id, $this->feb2016);
                $this->dstZone = new ZoneInfo($this->id, $this->apr2016);
            });

            it('->getAbbreviation()', function () {
                expect($this->zone->getAbbreviation())->toBe('EST');
                expect($this->dstZone->getAbbreviation())->toBe('EDT');
            });

            it('->getCountry()', function () {
                expect($this->zone->getCountry())->toBe('US');
            });

            it('->getDateTime()', function () {
                $dt = $this->zone->getDateTime();
                expect($dt)->toBeAnInstanceOf(DateTimeImmutable::class);
                expect($dt->getTimestamp())->toBe($this->feb2016);
            });

            it('->getTimestamp()', function () {
                expect($this->zone->getTimestamp())->toBe($this->feb2016);
            });

            it('->getUtcOffset()', function () {
                expect($this->zone->getUtcOffset())->toBe(-18000);
                expect($this->dstZone->getUtcOffset())->toBe(-14400);
            });

            it('->isDst()', function () {
                expect($this->zone->isDst())->toBe(false);

                $dstZone = new ZoneInfo($this->id, $this->apr2016);
                expect($dstZone->isDst())->toBe(true);
            });
        });
    });

    context('Europe', function () {
        context('London', function () {
            before(function () {
                $this->id = 'Europe/London';
                $this->zone = new ZoneInfo($this->id, $this->feb2016);
                $this->dstZone = new ZoneInfo($this->id, $this->apr2016);
            });

            it('->getAbbreviation()', function () {
                expect($this->zone->getAbbreviation())->toBe('GMT');
                expect($this->dstZone->getAbbreviation())->toBe('BST');
            });

            it('->getCountry()', function () {
                expect($this->zone->getCountry())->toBe('GB');
            });

            it('->getDateTime()', function () {
                $dt = $this->zone->getDateTime();
                expect($dt)->toBeAnInstanceOf(DateTimeImmutable::class);
                expect($dt->getTimestamp())->toBe($this->feb2016);
            });

            it('->getTimestamp()', function () {
                expect($this->zone->getTimestamp())->toBe($this->feb2016);
            });

            it('->getUtcOffset()', function () {
                expect($this->zone->getUtcOffset())->toBe(0);
                expect($this->dstZone->getUtcOffset())->toBe(3600);
            });

            it('->isDst()', function () {
                expect($this->zone->isDst())->toBe(false);

                $dstZone = new ZoneInfo($this->id, $this->apr2016);
                expect($dstZone->isDst())->toBe(true);
            });
        });

        context('Oslo', function () {
            before(function () {
                $this->id = 'Europe/Oslo';
                $this->zone = new ZoneInfo($this->id, $this->feb2016);
                $this->dstZone = new ZoneInfo($this->id, $this->apr2016);
            });

            it('->getAbbreviation()', function () {
                expect($this->zone->getAbbreviation())->toBe('CET');
                expect($this->dstZone->getAbbreviation())->toBe('CEST');
            });

            it('->getCountry()', function () {
                expect($this->zone->getCountry())->toBe('NO');
            });

            it('->getDateTime()', function () {
                $dt = $this->zone->getDateTime();
                expect($dt)->toBeAnInstanceOf(DateTimeImmutable::class);
                expect($dt->getTimestamp())->toBe($this->feb2016);
            });

            it('->getTimestamp()', function () {
                expect($this->zone->getTimestamp())->toBe($this->feb2016);
            });

            it('->getUtcOffset()', function () {
                expect($this->zone->getUtcOffset())->toBe(3600);
                expect($this->dstZone->getUtcOffset())->toBe(7200);
            });

            it('->isDst()', function () {
                expect($this->zone->isDst())->toBe(false);

                $dstZone = new ZoneInfo($this->id, $this->apr2016);
                expect($dstZone->isDst())->toBe(true);
            });
        });
    });
});
