<?php

use Kahlan\Filter\Filter;
use VCR\VCR;

$this->args()->argument('reporter', 'default', 'verbose');

VCR::configure()
    ->setCassettePath(__DIR__.'/spec/fixtures')
    ->enableRequestMatchers(['method', 'url', 'query_string', 'host']);

VCR::turnOn();

Filter::register('exclude.namespaces', function ($chain) {
});

Filter::apply($this, 'interceptor', 'exclude.namespaces');
