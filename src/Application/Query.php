<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application;

use App\Application\Query\ParameterReader;
use IteratorAggregate;

/**
 * Query
 *
 * @package App\Application
 */
interface Query extends IteratorAggregate
{

    /**
     * A key/value pairs of params to be used in query
     *
     * @return array
     */
    public function params(): array;

    /**
     * Check if a param is set
     *
     * @param string $name
     * @return bool
     */
    public function hasParam(string $name): bool;

    /**
     * Retrieves a param value or the provided default argument, when params is not set
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function param(string $name, mixed $default = null): mixed;

    /**
     * Set the parameter with provided name
     *
     * NOTE: Every call to this method MUST return a new instance, with empty data set.
     *
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function withParam(string $name, mixed $value): self;

    /**
     * Sets a custom parameter reader for pagination, filter and sorting
     *
     * @param ParameterReader $parameterReader
     * @return $this
     */
    public function withParameterReader(ParameterReader $parameterReader): self;
}
