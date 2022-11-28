<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory;

use ReflectionProperty;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Stringable;

/**
 * Tools
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory
 */
abstract class Tools
{

    /**
     * Retrieves a value form provided object property
     *
     * @param ReflectionProperty $property
     * @param object $object
     * @return string|int|bool|float|array|null
     */
    public static function getValue(ReflectionProperty $property, object $object): string|int|bool|float|array|null
    {
        $value = $property->getValue($object);
        if (is_scalar($value) || is_array($value) || is_null($value)) {
            return $value;
        }

        if ($value instanceof Stringable) {
            return (string) $value;
        }

        $className = $property->getDeclaringClass()->getName();
        $name = $property->getName();
        $valueClass = $property->getType()->getName();

        throw new DocumentEncoderFailure(
            "Couldn't extract the value of the '$className::$name()' property. ".
            "You should have class '$valueClass' implementing 'Stringable' interface"
        );
    }

    /**
     * Converts string from camelCase to snake_case
     *
     * @param string $input
     * @return string
     */
    public static function fromCamelCase(string $input): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}
