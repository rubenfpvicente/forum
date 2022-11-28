<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface\JsonApi;

use OpenApi\Attributes as OA;

/**
 * ApiEntityNotFound
 *
 * @package App\UserInterface\JsonApi
 */
#[
    OA\Schema(
        schema: "entityNotFound",
        properties: [
            new OA\Property(property: "jsonapi", ref: "#/components/schemas/jsonApiVersion"),
            new OA\Property(
                property: "errors",
                type: "array",
                items: new OA\Items(allOf: [
                    new OA\Schema(properties: [
                        new OA\Property(property: "id", type: "string", example: "6384ab03cd20e"),
                        new OA\Property(property: "status", type: "string", example: "404"),
                        new OA\Property(property: "title", type: "string", example: "The resource owner or authorization server denied the request."),
                        new OA\Property(property: "detail", type: "string", example: "Access token could not be verified"),
                    ])
                ])
            )
        ],
        type: "object"
    )
]
final class ApiEntityNotFound
{

}