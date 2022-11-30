<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory;

use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Relationship;
use Doctrine\Common\Collections\Collection;
use ReflectionProperty;

/**
 * AttributeParser
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory
 */
abstract class AttributeParser
{

    /**
     * Parse links o metadata
     *
     * @param AsResourceObject|Relationship $resourceObject
     * @return array|null
     */
    public static function parseLinks(AsResourceObject|Relationship $resourceObject): ?array
    {
        $def = $resourceObject->links ?: [];
        $links = [];
        foreach ($def as $linkDef) {
            $links[$linkDef] = true;
        }

        return !empty($links) ? $links : null;
    }

    /**
     * Parses meta data
     *
     * @param AsResourceObject|Relationship $resourceObject
     * @return array|null
     */
    public static function parseMeta(AsResourceObject|Relationship $resourceObject): ?array
    {
        return $resourceObject->meta ?: null;
    }

    /**
     * Parses relationship value depending on its type
     *
     * @param ReflectionProperty $property
     * @param object $object
     * @return mixed
     */
    public static function parseRelationshipValue(ReflectionProperty $property, object $object): mixed
    {
        $value = $property->getValue($object);
         if (!is_object($value)) {
             return $value;
         }

         if ($value instanceof Collection) {
             return $value->toArray();
         }

         if (is_iterable($value)) {
             $data = [];
             foreach ($value as $item) {
                 $data[] = $item;
             }
             return $data;
         }

         $methods = ['toArray', 'asArray'];
         foreach ($methods as $method) {
             if (method_exists($object, $method)) {
                 return $object->$method();
             }
         }

         return $value;
    }
}
