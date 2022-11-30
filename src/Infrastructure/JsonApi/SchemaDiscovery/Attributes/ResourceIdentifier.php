<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\Attributes;

use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\Tools;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\EncodableAttribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\PropertyAwareAttribute;
use Attribute;
use ReflectionProperty;

/**
 * ResourceIdentifier
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ResourceIdentifier implements EncodableAttribute
{

    private ?ReflectionProperty $property = null;

    /**
     * Creates a ResourceIdentifier
     *
     * @param string|null $type override and defines resource identifier type
     */
    public function __construct(
       public readonly ?string $type = null
    ) {}

    /**
     * @inheritDoc
     */
    public function retrieveValue(object $encodedObject): mixed
    {
        return Tools::getValue($this->property, $encodedObject);
    }

    /**
     * @inheritDoc
     */
    public function withProperty(ReflectionProperty $property): self
    {
        $this->property = $property;
        return $this;
    }
}
