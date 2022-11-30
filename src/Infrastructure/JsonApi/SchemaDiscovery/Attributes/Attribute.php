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
use Attribute as PHPAttribute;
use InvalidArgumentException;
use ReflectionProperty;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;
use Throwable;

/**
 * Attribute
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[PHPAttribute(PHPAttribute::TARGET_PROPERTY)]
class Attribute implements DecodableAttribute, EncodableAttribute
{

    private ?ReflectionProperty $property = null;

    /**
     * Creates an Attribute
     *
     * @param string|null $name JSON API attribute name
     * @param string|null $className Value Object class name when decoded
     * @param bool        $required Validate this attribute as required
     */
    public function __construct(
        public ?string $name = null,
        public ?string $className = null,
        public bool $required = false
    ) {
    }

    /**
     * Sets the reflection property to work with
     *
     * @param ReflectionProperty $property
     * @return Attribute
     */
    public function withProperty(ReflectionProperty $property): self
    {
        $this->property = $property;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function assignValue(object $decodedObject, ResourceObject $resourceObject): void
    {

        $attributes = $resourceObject->attributes();
        $name = $this->name ?: $this->property->getName();
        if (!array_key_exists($name, $attributes)) {
            return;
        }

        $rawValue = $attributes[$name];
        $className = $this->className;
        $value = $className ? new $className($rawValue) : $rawValue;
        $this->property->setValue($decodedObject, $value);
    }

    /**
     * @inheritDoc
     */
    public function validate(ResourceObject $resourceObject, SchemaDecodeValidator $validator): void
    {
        $name = $this->name ?: $this->property->getName();

        if (!$this->checkRequired($name, $resourceObject, $validator)) {
            return;
        }

        if (!$this->hasAttribute($name, $resourceObject)) {
            return;
        }

        if (!$this->className) {
            return;
        }

        $this->verifyObjectCreation($name, $resourceObject, $validator);
    }

    /**
     * @inheritDoc
     */
    public function retrieveValue(object $encodedObject): string|array|bool|int|null|float
    {
        return Tools::getValue($this->property, $encodedObject);
    }

    /**
     * Check if resource object has a given attribute.
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @return bool
     */
    private function hasAttribute(string $name, ResourceObject $resourceObject): bool
    {
        return $resourceObject->attributes() && array_key_exists($name, $resourceObject->attributes());
    }

    /**
     * Retrieves the attribute with given name from provided resource object
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @return mixed
     */
    private function attribute(string $name, ResourceObject $resourceObject): mixed
    {
        if (!$this->hasAttribute($name, $resourceObject)) {
            return null;
        }

        return $resourceObject->attributes()[$name];
    }

    /**
     * Checks if a required attribute exists
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @param SchemaDecodeValidator $validator
     * @return bool
     */
    private function checkRequired(string $name, ResourceObject $resourceObject, SchemaDecodeValidator $validator): bool
    {
        if ($this->required && !$this->hasAttribute($name, $resourceObject)) {
            $validator->add(
                title: "Missing '$name' attribute",
                detail: "Attribute '$name' is mandatory, but it isn't present in the requested resource object.",
                source: new ErrorSource("/data/attributes/$name"),
                status: "400"
            );
            return false;
        }

        return true;
    }

    /**
     * Verifies if it's possible to create the attribute value with a custom class
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @param SchemaDecodeValidator $validator
     */
    private function verifyObjectCreation(string $name, ResourceObject $resourceObject, SchemaDecodeValidator $validator): void
    {
        try {
            new ($this->className)($this->attribute($name, $resourceObject));
        } catch (InvalidArgumentException $e) {
            $validator->add(
                title: "Attribute '$name' is invalid",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/attributes/$name"),
                status: "400"
            );
            return;
        } catch (Throwable $e) {
            $validator->add(
                title: "Couldn't create value from attribute '$name'",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/attributes/$name"),
                status: "500"
            );
        }
    }
}
