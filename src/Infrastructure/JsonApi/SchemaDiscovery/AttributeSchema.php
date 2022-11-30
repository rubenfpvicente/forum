<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Relationship;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\RelationshipIdentifier;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;
use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\AttributeParser;
use Ramsey\Uuid\Uuid;
use ReflectionException;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

/**
 * AttributeSchema
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
final class AttributeSchema implements ResourceSchema
{

    /** @var array<string, Attribute>  */
    private array $attributes;

    /** @var array<string, Relationship>|null  */
    private ?array $relationships;

    /** @var array<string, RelationshipIdentifier> */
    private array $relationshipIdentifiers;

    /**
     * Creates a AttributeSchema
     *
     * @param AsResourceObject $resourceObject
     * @param array $attributes
     * @param array|null $relationships
     * @param ResourceIdentifier|null $resourceIdentifier
     * @param array $relationshipIdentifiers
     */
    public function __construct(
        private readonly AsResourceObject    $resourceObject,
        array                                $attributes = [],
        ?array                               $relationships = null,
        private readonly ?ResourceIdentifier $resourceIdentifier = null,
        array                                $relationshipIdentifiers = []
    ) {
        $this->attributes = $attributes;
        $this->relationships = $relationships;
        $this->relationshipIdentifiers = $relationshipIdentifiers;
    }

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return $this->resourceObject->isCompound;
    }

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        if ($this->resourceIdentifier->type) {
            return $this->resourceIdentifier->type;
        }

        return $this->resourceObject->parseType();
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        if ($this->resourceIdentifier) {
            return $this->resourceIdentifier->retrieveValue($object);
        }

        return Uuid::uuid4()->toString();
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        $attributes = [];
        foreach ($this->attributes as $name => $attribute) {
            $attributes[$name] = $attribute->retrieveValue($object);
        }
        return $attributes;
    }

    /**
     * @inheritDoc
     */
    public function relationships($object): ?array
    {
        if (!$this->relationships) {
            return null;
        }

        $relationships = [];
        foreach ($this->relationships as $name => $relationship) {
            $value = $relationship->retrieveValue($object);
            if ($relationship->type === Relationship::TO_ONE && !$value) {
                continue;
            }

            $data = [
                "data" => $value,
            ];

            $links = AttributeParser::parseLinks($this->resourceObject);
            if ($links) {
                $data["links"] = $links;
            }

            $meta = AttributeParser::parseMeta($this->resourceObject);
            if ($meta) {
                $data["meta"] = $meta;
            }

            $relationships[$name] = $data;
        }

        return $relationships;
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return AttributeParser::parseLinks($this->resourceObject);
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return AttributeParser::parseMeta($this->resourceObject);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function from($resourceObject)
    {
        $decodedObject = $this->resourceObject->createInstance();
        foreach ($this->attributes as $attribute) {
            $attribute->assignValue($decodedObject, $resourceObject);
        }

        foreach ($this->relationshipIdentifiers as $relationshipIdentifier) {
            $relationshipIdentifier->assignValue($decodedObject, $resourceObject);
        }
        return $decodedObject;
    }

    /**
     * @inheritDoc
     */
    public function validate($resourceObject, SchemaDecodeValidator $validator): void
    {
        foreach ($this->attributes as $attribute) {
            $attribute->validate($resourceObject, $validator);
        }

        foreach ($this->relationshipIdentifiers as $relationshipIdentifier) {
            $relationshipIdentifier->validate($resourceObject, $validator);
        }
    }
}
