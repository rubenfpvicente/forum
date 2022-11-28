<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\UserInterface\JsonApi;

use OpenApi\Attributes as OA;

#[
    OA\Schema(
        schema: "jsonApiVersion",
        properties: [
            new OA\Property(property: "version", enum: ["1.0", "1.1"], example: "1.1")
        ],
        type: "object"
    )
]
class JsonApiVersion
{
}