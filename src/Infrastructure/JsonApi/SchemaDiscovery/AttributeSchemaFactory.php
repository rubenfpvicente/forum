<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\AttributeParser;
use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\PropertyConfigurationMethods;
use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\Tools;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use ReflectionProperty;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover\ArraySchema;

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
     * @param object $object
     * @return ResourceSchema
     */
    public function createSchemaFor(object $object): ResourceSchema
    {
        $schemaClass = $this->asResourceObjectAttr->schemaClass;
        if ($schemaClass) {
            return new $schemaClass($object);
        }

        $data = $this->createData($object);

        AttributeParser::parseLinks($data, $this->asResourceObjectAttr);
        AttributeParser::parseMeta($data, $this->asResourceObjectAttr);
        AttributeParser::parseRelationships($data, $this->relationships, $object);

        return $this->asResourceObjectAttr instanceof AsResourceCollection
            ? new ResourceCollectionSchema($data)
            : new ArraySchema($data);
    }

    /**
     * Creates a basic data array to use with array schema.
     *
     * @param object $object
     * @return array
     */
    private function createData(object $object): array
    {
        return [
            'type' => $this->type ?: AttributeParser::parseType($object, $this->asResourceObjectAttr),
            'identifier' => $this->identifierField ? Tools::getValue($this->identifierField, $object) : Uuid::uuid4()->toString(),
            'attributes' => AttributeParser::parseAttributes($object, $this->attributes),
            'isCompound' => $this->asResourceObjectAttr->isCompound
        ];
    }
}
