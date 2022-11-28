<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory;

use App\Infrastructure\JsonApi\SchemaDiscovery\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Relationship;
use App\Infrastructure\JsonApi\SchemaDiscovery\ResourceIdentifier;
use ReflectionClass;
use ReflectionProperty;

/**
 * PropertyConfigurationMethods trait
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory
 */
trait PropertyConfigurationMethods
{

    private ?ReflectionProperty $identifierField = null;
    private ?string $type = null;
    /** @var array<string, ReflectionProperty>  */
    private array $attributes = [];
    private ?array $relationships = null;

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
        $this->type = $attribute->type;
        $this->identifierField = $property;
    }

    /**
     * Configures attribute entry
     *
     * @param Attribute $instance
     * @param ReflectionProperty $property
     */
    private function configureAttribute(Attribute $instance, ReflectionProperty $property): void
    {
        $this->attributes[$instance->name ?: $property->getName()] = $property;
    }

    /**
     * Configures relationship entry
     *
     * @param Relationship $instance
     * @param ReflectionProperty $property
     */
    private function configureRelationship(Relationship $instance, ReflectionProperty $property): void
    {
        $this->relationships[$instance->name ?: $property->name] = $property;
    }
}
