<?php
use filter\Filter;
use VCR\VCR;

$this->args()->argument('reporter', 'default', 'verbose');

VCR::configure()->setCassettePath(__DIR__.'/spec/fixtures');
VCR::turnOn();

Filter::register('exclude.namespaces', function ($chain) {
});

Filter::apply($this, 'interceptor', 'exclude.namespaces');