<?php

namespace Teazee\Spec\Provider;

use Kahlan\Plugin\Stub;
use Teazee\Exception\ChainNoResult;
use Teazee\Provider\Chain;
use Teazee\Provider\Provider;
use Teazee\ZoneInfo;

describe(Chain::class, function () {
    it('is a '.Provider::class, function () {
        expect(new Chain())->toBeAnInstanceOf(Provider::class);
    });

    it('->getName()', function () {
        expect((new Chain())->getName())->toBe('chain');
    });

    context('->find()', function () {
        before(function () {
            $this->lat = 40.1146914;
            $this->lng = -88.3121289;
        });

        context('without providers', function () {
            it('throws an exception', function () {
                expect(function () {
                    $chain = new Chain();
                    $chain->find($this->lat, $this->lng);
                })->toThrow();
            });
        });

        context('with multiple providers', function () {
            beforeEach(function () {
                $this->time = time();

                $this->p1 = Stub::create();
                Stub::on($this->p1)->method('find', function ($lat, $lng, $timestamp = null) {
                    return new ZoneInfo('America/Chicago', $timestamp);
                });

                $this->p2 = Stub::create();
                Stub::on($this->p2)->method('find', function ($lat, $lng, $timestamp = null) {
                    throw new \RuntimeException();
                });
            });

            context('when the first result succeeds', function () {
                it('returns the first successful result', function () {
                    $chain = new Chain([$this->p1, $this->p2]);

                    $zone = $chain->find($this->lat, $this->lng, $this->time);

                    expect($zone->getTimestamp())->toBe($this->time);
                });

                it('will not call any subsequent providers', function () {
                    expect($this->p2)->not->toReceive('find');

                    $chain = new Chain([$this->p1, $this->p2]);

                    $chain->find($this->lat, $this->lng, $this->time);
                });
            });

            context('when the first result fails', function () {
                it('will attempt to use the second provider', function () {
                    $chain = new Chain([$this->p2, $this->p1]);
                    $zone = $chain->find($this->lat, $this->lng, $this->time);

                    expect($zone->getTimestamp())->toBe($this->time);
                });
            });

            context('when all results fail', function () {
               it('will return the failures', function () {
                   try {
                       $chain = new Chain([$this->p2, $this->p2]);
                       $chain->find($this->lat, $this->lng);
                   } catch (ChainNoResult $ex) {
                       expect($ex->getExceptions())->toHaveLength(2);
                   }
               });
            });
        });
    });
});
