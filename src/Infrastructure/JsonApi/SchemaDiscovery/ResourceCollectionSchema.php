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
use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\AttributeParser;
use IteratorAggregate;
use Ramsey\Uuid\Uuid;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\AbstractResourceSchema;
use Slick\JSONAPI\Object\ResourceCollectionSchema as JSONAPIResourceCollectionSchema;

/**
 * ResourceCollectionSchema
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
final class ResourceCollectionSchema extends AbstractResourceSchema implements JSONAPIResourceCollectionSchema
{

    public function __construct(
        private readonly AsResourceCollection $asResourceCollection
    ) {
    }

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return $this->asResourceCollection->parseType();
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        if ($object instanceof IteratorAggregate) {
            $data = [];
            foreach ($object as $value) {
                $data[] = $value;
            }
            return $data;
        }

        $possibleMethods = ['toArray', 'asArray'];
        foreach ($possibleMethods as $method) {
            if (method_exists($object, $method)) {
                return $object->$method();
            }
        }

        $key = get_class($object);

        throw new DocumentEncoderFailure(
            "Couldn't create a resource collection schema of the resource '$key'. " .
            "Try to implement 'IteratorAggregate' or add a method named '$key::toArray()'."
        );
    }

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return $this->asResourceCollection->isCompound;
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        $links = AttributeParser::parseLinks($this->asResourceCollection);
        $linksFromMethod = $this->asResourceCollection->linksFromMethod;
        if ($linksFromMethod) {
            $links = is_array($links) ? $links : [];
            $links = array_merge($links, $object->$linksFromMethod());
        }

        return $links;
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        $meta = AttributeParser::parseMeta($this->asResourceCollection);
        $metaFromMethod = $this->asResourceCollection->metaFromMethod;
        if ($metaFromMethod) {
            $meta = $object->$metaFromMethod();
        }

        return $meta;
    }


}
