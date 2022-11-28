<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi;

use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaDiscover;
use App\Infrastructure\JsonApi\SchemaDiscovery\ClassMapSchemaDiscovery;
use ReflectionException;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * JsonApiSchemaDiscover
 *
 * @package App\Infrastructure\JsonApi
 */
final class JsonApiSchemaDiscover implements SchemaDiscover
{
    private const CLASS_MAP = ClassMapSchemaDiscovery::class;
    private const ATTRIBUTE = AttributeSchemaDiscover::class;

    /** @var array<string,ClassMapSchemaDiscovery|AttributeSchemaDiscover> */
    private array $instances;

    /**
     * Creates a JsonApiSchemaDiscover
     *
     * @param string $namespacePrefix
     * @param string $psr4prefix
     */
    public function __construct(
        private readonly string $namespacePrefix = "App\\Infrastructure\\JsonApi\\",
        private readonly string $psr4prefix      = "App\\",
        private readonly array  $ignoredNames    = ["Domain\\", "Application\\"]
    ) {
        $this->instances[self::ATTRIBUTE] = new AttributeSchemaDiscover();
        $this->instances[self::CLASS_MAP] = new ClassMapSchemaDiscovery($this->namespacePrefix, $this->psr4prefix, $this->ignoredNames);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException|DocumentEncoderFailure
     */
    public function discover($object): ResourceSchema
    {
        if (is_array($object)) {
            return new SchemaDiscover\ArraySchema($object);
        }

        foreach ($this->instances as $schemaDiscover) {
            if ($schemaDiscover->isConvertible($object)) {
                return $schemaDiscover->discover($object);
            }
        }

        $key = get_class($object);
        throw new DocumentEncoderFailure(
            "Couldn't create a resource schema of the resource '$key'. " .
            "No JSON API attribute or ResourceSchema was defined for it."
        );
    }

    /**
     * @inheritDoc
     */
    public function map(string $key, $className): SchemaDiscover
    {
        $this->instances[self::CLASS_MAP]->map($key, $className);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isConvertible($object): bool
    {
        if (is_array($object)) {
            return true;
        }

        foreach ($this->instances as $instance) {
            if ($instance->isConvertible($object)) {
                return true;
            }
        }

        return false;
    }
}
