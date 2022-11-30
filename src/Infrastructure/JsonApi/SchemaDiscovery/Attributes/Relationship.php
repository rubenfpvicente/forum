<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\Attributes;

use App\Infrastructure\JsonApi\SchemaDiscovery\AttributeSchemaFactory\AttributeParser;
use Attribute;
use ReflectionProperty;

/**
 * Relationship
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Relationship implements EncodableAttribute
{
    public const TO_MANY = 'to_many';
    public const TO_ONE  = 'to_one';

    private ?ReflectionProperty $property = null;

    /**
     * Creates a Relationship
     *
     * @param string      $type relationship type
     * @param string|null $name resource document relationship name
     * @param array|null  $links a list of resource links
     * @param array|null  $meta a key/value pair of meta data to send out on resource document
     */
    public function __construct(
        public readonly string $type = self::TO_ONE,
        public readonly ?string $name = null,
        public readonly ?array $links = null,
        public readonly ?array $meta = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function withProperty(ReflectionProperty $property): PropertyAwareAttribute
    {
        $this->property = $property;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function retrieveValue(object $encodedObject): mixed
    {
        return AttributeParser::parseRelationshipValue($this->property, $encodedObject);
    }
}
