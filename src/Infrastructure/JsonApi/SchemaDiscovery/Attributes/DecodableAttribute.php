<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\Attributes;

use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

/**
 * DecodableAttribute
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
interface DecodableAttribute extends PropertyAwareAttribute
{

    /**
     * Assign the value to provided instance from document object
     *
     * @param object $decodedObject
     * @param ResourceObject $resourceObject
     */
    public function assignValue(object $decodedObject, ResourceObject $resourceObject): void;

    /**
     * Validate current value on resource document
     *
     * @param ResourceObject $resourceObject
     * @param SchemaDecodeValidator $validator
     */
    public function validate(ResourceObject $resourceObject, SchemaDecodeValidator $validator): void;
}
