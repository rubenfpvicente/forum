<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory;

use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Relationship;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\RelationshipIdentifier;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;
use ReflectionClass;
use ReflectionProperty;

/**
 * PropertyConfigurationMethods trait
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory
 */
trait PropertyConfigurationMethods
{

    /** @var array<string, Attribute>  */
    private array $attributes = [];

    /** @var array<string, Relationship>|null  */
    private ?array $relationships = null;

    private ?ResourceIdentifier $resourceIdentifier = null;

    /** @var array<string, RelationshipIdentifier>  */
    private array $relationshipIdentifiers = [];

    /**
     * Read all class property attributes
     *
     * @param ReflectionClass $reflection
     */
    private function readAllAttributes(ReflectionClass $reflection): void
    {
        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes();
            $this->configureAttributes($attributes, $property);
        }
    }

    /**
     * Configures the list off attributes
     *
     * @param array $attributes
     * @param ReflectionProperty $property
     */
    private function configureAttributes(array $attributes, ReflectionProperty $property): void
    {
        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof ResourceIdentifier) {
                $this->configureResourceIdentifier($instance, $property);
                continue;
            }

            if ($instance instanceof Attribute) {
                $this->configureAttribute($instance, $property);
                continue;
            }

            if ($instance instanceof Relationship) {
                $this->configureRelationship($instance, $property);
                continue;
            }

            if ($instance instanceof RelationshipIdentifier) {
                $this->configureRelationshipIdentifier($instance, $property);
            }
        }
    }

    /**
     * Configures the resource identifier method
     *
     * @param ResourceIdentifier $attribute
     * @param ReflectionProperty $property
     */
    private function configureResourceIdentifier(ResourceIdentifier $attribute, ReflectionProperty $property): void
    {
        $this->resourceIdentifier = $attribute->withProperty($property);
    }

    /**
     * Configures attribute entry
     *
     * @param Attribute $instance
     * @param ReflectionProperty $property
     */
    private function configureAttribute(Attribute $instance, ReflectionProperty $property): void
    {
        $this->attributes[$instance->name ?: $property->getName()] = $instance->withProperty($property);
    }

    /**
     * Configures relationship entry
     *
     * @param Relationship $instance
     * @param ReflectionProperty $property
     */
    private function configureRelationship(Relationship $instance, ReflectionProperty $property): void
    {
        $this->relationships[$instance->name ?: $property->name] = $instance->withProperty($property);
    }

    /**
     * Configures relationship identifiers' list
     *
     * @param RelationshipIdentifier $instance
     * @param ReflectionProperty $property
     */
    private function configureRelationshipIdentifier(RelationshipIdentifier $instance, ReflectionProperty $property): void
    {
        $this->relationshipIdentifiers[$instance->name ?: $property->name] = $instance->withProperty($property);
    }
}
