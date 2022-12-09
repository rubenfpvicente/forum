<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceCollection;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\PropertyConfigurationMethods;
use Doctrine\Common\Collections\Collection;
use ReflectionClass;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * AttributeSchemaFactory
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
final class AttributeSchemaFactory
{
    use PropertyConfigurationMethods;

    /**
     * Creates a AttributeSchemaFactory
     *
     * @param AsResourceObject $asResourceObjectAttr
     * @param ReflectionClass $reflection
     */
    public function __construct(
        private readonly AsResourceObject $asResourceObjectAttr,
        private readonly ReflectionClass  $reflection
    ) {
        $this->readAllAttributes($this->reflection);
    }

    /**
     * Create a schema for provided object
     *
     * @param object|string $object
     * @return ResourceSchema
     */
    public function createSchemaFor(object|string $object): ResourceSchema
    {
        $schemaClass = $this->asResourceObjectAttr->schemaClass;
        if ($schemaClass) {
            return new $schemaClass($object);
        }

        return $this->asResourceObjectAttr instanceof AsResourceCollection
            ? new ResourceCollectionSchema($this->asResourceObjectAttr)
            : new AttributeSchema(
                resourceObject: $this->asResourceObjectAttr,
                attributes: $this->attributes,
                relationships: $this->relationships,
                resourceIdentifier: $this->resourceIdentifier,
                relationshipIdentifiers: $this->relationshipIdentifiers
            );
    }

    public function parseArrayFrom($object): array
    {
        if (is_array($object)) {
            return $object;
        }

        if ($object instanceof Collection) {
            return $object->toArray();
        }

        if ($object instanceof \IteratorAggregate) {
            return $this->parseArrayFrom($object->getIterator());
        }

        return [];
    }

}
