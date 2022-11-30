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
use ReflectionClass;
use ReflectionException;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * AttributeSchemaDiscover
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
final class AttributeSchemaDiscover implements SchemaDiscover
{
    /**
     * @var array<string, AttributeSchemaFactory>
     */
    private array $map = [];

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function discover($object): ResourceSchema
    {
        $key = is_string($object) ? $object : get_class($object);
        if (array_key_exists($key, $this->map)) {
            return $this->map[$key]->createSchemaFor($object);
        }

        if (!$this->isConvertible($object)) {
            throw new DocumentEncoderFailure(
                "Couldn't create a resource schema of the resource '$key'. " .
                "Try to add the attribute 'AsResourceObject' or 'AsResourceCollection' to the '$key' class."
            );
        }

        return $this->map[$key]->createSchemaFor($object);
    }

    /**
     * @inheritDoc
     * @param AttributeSchemaFactory $className
     */
    public function map(string $key, $className): SchemaDiscover
    {
        $this->map[$key] = $className;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function isConvertible($object): bool
    {
        $key = is_string($object) ? $object : get_class($object);
        if (array_key_exists($key, $this->map)) {
            return true;
        }

        return $this->verifyAttributeIsPresent($object, $key);
    }

    /**
     * isPresent
     *
     * @param mixed $object
     * @param string $key
     * @return bool
     * @throws ReflectionException
     */
    private function verifyAttributeIsPresent(mixed $object, string $key): bool
    {
        $reflection = new ReflectionClass($object);
        $resource = $this->attributeInstance($reflection);


        if ($resource) {
            $this->map($key, new AttributeSchemaFactory($resource, $reflection));
        }
        return (bool) $resource;
    }

    /**
     * Retrieves class attribute instance
     *
     * @param ReflectionClass $reflection
     * @return AsResourceObject|null
     */
    private function attributeInstance(ReflectionClass $reflection): ?AsResourceObject
    {
        $attributes = [AsResourceObject::class, AsResourceCollection::class];
        foreach ($attributes as $attribute) {
            $asResourceObjectAttr = $reflection->getAttributes($attribute);
            if (count($asResourceObjectAttr) > 0) {
                $instance = $asResourceObjectAttr[0]->newInstance();
                return $instance->withClass($reflection);
            }
        }

        return null;
    }
}
