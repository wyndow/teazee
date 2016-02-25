<?php

/**
 * This file is part of the Teazee package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */
namespace Teazee\Exception;

/**
 * @author Michael Crumm <mike@crumm.net>
 */
class ChainNoResult extends NoResult
{
    /**
     * @var array
     */
    private $exceptions = [];

    /**
     * ChainNoResult constructor.
     *
     * @param string $message
     * @param array  $exceptions Array of Exception instances.
     */
    public function __construct($message, array $exceptions = [])
    {
        $this->exceptions = $exceptions;

        parent::__construct($message);
    }

    /**
     * Returns the exceptions from the chained providers.
     *
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
