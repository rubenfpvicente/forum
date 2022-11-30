<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use Doctrine\Common\Collections\Collection;
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

    public function __construct(private readonly iterable $data)
    {
    }

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return $this->data['type'];
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return $this->data['identifier'];
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        if ($object instanceof \IteratorAggregate) {
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
        $key = 'isCompound';
        return array_key_exists($key, $this->data) && $this->data[$key];
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return $this->dataValue('links');
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return $this->dataValue('meta');
    }

    /**
     * extracted
     *
     * @param string $key
     * @return mixed|null
     */
    private function dataValue(string $key): mixed
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }
}
