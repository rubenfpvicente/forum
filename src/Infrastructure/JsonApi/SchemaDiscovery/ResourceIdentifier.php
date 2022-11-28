<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use Attribute;

/**
 * ResourceIdentifier
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ResourceIdentifier
{

    /**
     * Creates a ResourceIdentifier
     *
     * @param string|null $type
     */
    public function __construct(
       public readonly ?string $type = null
    ) {}
}