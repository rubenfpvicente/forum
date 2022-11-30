<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\Attributes;

use ReflectionProperty;

/**
 * PropertyAwareAttribute
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
interface PropertyAwareAttribute
{

    /**
     * Sets the reflection property to work with
     *
     * @param ReflectionProperty $property
     * @return self
     */
    public function withProperty(ReflectionProperty $property): self;
}
