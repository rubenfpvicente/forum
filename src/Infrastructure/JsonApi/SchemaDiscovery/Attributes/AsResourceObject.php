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
use Attribute;
use ReflectionClass;
use ReflectionException;
use Slick\JSONAPI\Object\ResourceSchema;

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

    private ?ReflectionClass $class = null;

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

    /**
     * Sets the reflection class for this resource
     *
     * @param ReflectionClass $class
     * @return AsResourceObject
     */
    public function withClass(ReflectionClass $class): self
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Creates an instance of current reflection class.
     *
     * @return object
     * @throws ReflectionException
     */
    public function createInstance(): object
    {
        return $this->class->newInstanceWithoutConstructor();
    }

    /**
     * Parses the resource object type.
     *
     * @return string
     */
    public function parseType(): string
    {
        $nameParts = explode("\\", $this->class->getName());
        $type = Tools::fromCamelCase(strtolower(end($nameParts)));
        if ($this->type) {
            $type = $this->type;
        }
        return $type;
    }
}
