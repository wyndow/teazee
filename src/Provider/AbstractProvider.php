<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Provider;

use DateTimeImmutable;
use DateTimeZone;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
abstract class AbstractProvider implements Provider
{
    /**
     * Returns the current time as seconds since January 1, 1970 UTC.
     *
     * @return int
     */
    protected function getCurrentTimestamp()
    {
        return (new DateTimeImmutable(null, new DateTimeZone('UTC')))->getTimestamp();
    }
}
