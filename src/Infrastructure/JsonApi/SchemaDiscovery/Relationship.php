<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery;

use Attribute;
use phpDocumentor\Reflection\Type;

/**
 * Relationship
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Relationship
{
    public const TO_MANY = 'to_many';
    public const TO_ONE  = 'to_one';

    public function __construct(
        public readonly string $type = self::TO_ONE,
        public readonly ?string $name = null,
        public readonly ?array $links = null,
        public readonly ?array $meta = null
    ) {
    }
}
