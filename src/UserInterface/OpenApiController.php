<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface;

use OpenApi\Attributes as OA;
use OpenApi\Generator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * JsonApiController
 *
 * @package App\UserInterface
 */
#[
    OA\Info(
        version: 'v0.1.0',
        description: "This is a simple API that have the forum 'StackOverflow' as an inspiration.

It's develop with Symfony 6 and applying DDD and TDD techniques with _PHP 8.1_.
It is also have ideas of the hexagonal application architecture to separate the several layers of the application by its responsibility.

This project was made is a practical activity made by students of Azores' University in the class of 'Application development using frameworks'.",
        title: "DAF Forum API"
    ),
    OA\Server(url: "http://192.168.56.118", description: "Development server"),
    OA\SecurityScheme(
        securityScheme: "OAuth2",
        type: "oauth2",
        description: "This API uses OAuth2 with the authorization code grant flow",
        flows: [
            new OA\Flow(
                tokenUrl: "/access-token",
                refreshUrl: "/access-token",
                flow: "password",
                scopes: [
                    "forum" => "Manage your owned forum resources."
                ]
            )
        ]
    ),
    OA\Contact(name: "Tutor: Filipe Silva",email: "filipe.mm.silva@uac.pt"),
    OA\Tag(name: "User Management", description: "A collection of endpoints that allows retrieve user information resources.")

]
final class OpenApiController extends AbstractController
{


    #[Route(path: "open-api.json")]
    public function openApiFile(): Response
    {
        $dirs = dirname(dirname(__DIR__)) . '/src';
        $openapi = Generator::scan([$dirs]);
        return new Response($openapi->toJson(), 200, ['content-type' => 'application/json']);
    }
}

