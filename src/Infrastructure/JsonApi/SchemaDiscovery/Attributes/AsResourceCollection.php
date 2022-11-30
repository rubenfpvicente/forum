<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\SchemaDiscovery\Attributes;

use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use Attribute;

/**
 * AsResourceCollection
 *
 * @package App\Infrastructure\JsonApi\SchemaDiscovery
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsResourceCollection extends AsResourceObject
{

}
