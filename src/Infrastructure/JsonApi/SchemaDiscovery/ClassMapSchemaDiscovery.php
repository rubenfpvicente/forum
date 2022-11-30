<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * ClassMapSchemaDiscovery
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
final class ClassMapSchemaDiscovery implements SchemaDiscover
{

    private array $map = [];

    /**
     * Creates a ClassMapSchemaDiscovery
     *
     * @param string $namespacePrefix
     * @param string $psr4prefix
     * @param array $ignoredNames
     */
    public function __construct(
        private readonly string $namespacePrefix = "App\\Infrastructure\\JsonApi\\",
        private readonly string $psr4prefix = "App\\",
        private readonly array $ignoredNames = ["Domain\\", "Application\\"]
    ) {
    }

    /**
     * @inheritDoc
     */
    public function discover($object): ResourceSchema
    {
        $key = is_string($object) ? $object : get_class($object);

        if (!$this->isConvertible($object)) {
            $suggestion = $this->constructSchemaClassName($object);
            throw new DocumentEncoderFailure(
                "Couldn't create a resource schema of the resource '$key'. " .
                "Try to create the class '$suggestion', implementing the 'Slick\\JSONAPI\\Object\\ResourceSchema' interface."
            );
        }

        $className = array_key_exists($key, $this->map)
            ? $this->map[$key]
            : $this->constructSchemaClassName($object);


        if (in_array(ResourceSchema::class, class_implements($className))) {
            return new $className;
        }

        throw new DocumentEncoderFailure(
            "'$className' does not implement ResourceSchema::class. A schema could not be created."
        );
    }

    /**
     * @inheritDoc
     */
    public function map(string $key, $className): SchemaDiscover
    {
        $this->map[$key] = $className;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isConvertible($object): bool
    {
        $key = is_string($object) ? $object : get_class($object);
        if (array_key_exists($key, $this->map)) {
            return true;
        }

        return $this->schemaClassIsDefined($object);
    }

    /**
     * Check if schema class is defined
     *
     * @param mixed $object
     * @return bool
     */
    private function schemaClassIsDefined(mixed $object): bool
    {
        $schemaClass = $this->constructSchemaClassName($object);
        if (class_exists($schemaClass)) {
            $className = is_string($object) ? $object : get_class($object);
            $this->map($className, $schemaClass);
            return true;
        }

        return false;
    }

    /**
     * Construct Schema Class Name for a given object
     *
     * @param mixed $object
     * @return string
     */
    private function constructSchemaClassName(mixed $object): string
    {
        $replace = [];
        $needle = [];
        foreach ($this->ignoredNames as $name) {
            $replace[] = '';
            $needle[] = $name;
        }

        $replace[] = $this->namespacePrefix;
        $needle[] = $this->psr4prefix;
        $get_class = is_string($object) ? $object : get_class($object);
        return str_replace($needle, $replace, $get_class) . 'Schema';
    }
}
