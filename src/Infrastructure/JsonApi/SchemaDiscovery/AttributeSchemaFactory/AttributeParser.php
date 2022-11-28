<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory;

use App\Infrastructure\JsonApi\SchemaDiscovery\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Relationship;
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
     * Parses the document type property
     *
     * @param object $object
     * @param AsResourceObject $resourceObject
     * @return string|null
     */
    public static function parseType(object $object, AsResourceObject $resourceObject): ?string
    {
        $nameParts = explode("\\", get_class($object));
        $type = Tools::fromCamelCase(strtolower(end($nameParts)));

        if ($resourceObject->type) {
            $type = $resourceObject->type;
        }


        return $type;
    }

    /**
     * Parse object attribute values
     *
     * @param object $object
     * @param array $attributeList
     * @return array<string, ReflectionProperty>
     */
    public static function parseAttributes(object $object, array $attributeList): array
    {
        $data = [];
        foreach ($attributeList as $name => $property) {
            $data[$name] = Tools::getValue($property, $object);
        }
        return $data;
    }

    /**
     * Parse links o metadata
     *
     * @param array $data
     * @param AsResourceObject|Relationship $resourceObject
     */
    public static function parseLinks(array &$data, AsResourceObject|Relationship $resourceObject): void
    {
        $def = $resourceObject->links ?: [];
        $links = [];
        foreach ($def as $linkDef) {
            $links[$linkDef] = true;
        }

        if (!empty($links)) {
            $data['links'] = $links;
        }
    }

    /**
     * Parses meta data
     *
     * @param array $data
     * @param AsResourceObject|Relationship $resourceObject
     */
    public static function parseMeta(array &$data, AsResourceObject|Relationship $resourceObject): void
    {
        if (!$resourceObject->meta) {
            return;
        }

        $data['meta'] = $resourceObject->meta;
    }

    /**
     * Parse relationships
     *
     * @param array $data
     * @param array<string, ReflectionProperty>|null $relationshipsList
     * @param object $object
     */
    public static function parseRelationships(array &$data, ?array $relationshipsList, object $object): void
    {
        if (!$relationshipsList) {
            return;
        }

        $relationships = [];
        foreach ($relationshipsList as $name => $property) {
            $value = self::parseRelationshipValue($property, $object);
            /** @var Relationship $relationshipAttr */
            $relationshipAttr = $property->getAttributes(Relationship::class)[0]->newInstance();

            if ($relationshipAttr->type === Relationship::TO_ONE && !$value) {
                continue;
            }

            $data = [
                'data' => $value
            ];
            AttributeParser::parseMeta($data, $relationshipAttr);
            AttributeParser::parseLinks($data, $relationshipAttr);

            $relationships[$name] = $data;
        }



        if (!empty($relationships)) {
            $data['relationships'] = $relationships;
        }
    }

    /**
     * Parses relationship value depending on its type
     *
     * @param ReflectionProperty $property
     * @param object $object
     * @return mixed
     */
    private static function parseRelationshipValue(ReflectionProperty $property, object $object): mixed
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
