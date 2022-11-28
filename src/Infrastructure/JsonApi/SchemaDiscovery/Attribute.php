<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

/**
 * Attribute
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Attribute
{

    public function __construct(public ?string $name = null)
    {
    }
}