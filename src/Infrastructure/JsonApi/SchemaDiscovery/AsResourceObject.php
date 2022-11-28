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
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * AsResourceObject
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsResourceObject
{
    public const LINK_SELF = ResourceSchema::LINK_SELF;
    public const LINK_RELATED = ResourceSchema::LINK_RELATED;

    /**
     * Creates a AsResourceObject
     *
     * @param string|null $type
     * @param array|null $meta
     * @param array|null $links
     * @param string|null $schemaClass
     * @param bool $isCompound
     */
    public function __construct(
        public readonly ?string $type = null,
        public readonly ?array $meta = null,
        public readonly ?array $links = null,
        public readonly ?string $schemaClass = null,
        public readonly bool $isCompound = false
    ) {}
}
