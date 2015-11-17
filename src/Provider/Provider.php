<?php

namespace Teazee\Provider;

use Teazee\Teazee;

interface Provider extends Teazee
{
    /**
     * @return string
     */
    public function getName();
}
