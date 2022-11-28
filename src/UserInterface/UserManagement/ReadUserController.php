<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\UserInterface\UserManagement;

use App\Domain\Exception\EntityNotFound;
use App\Domain\Exception\InvalidAggregateIdentifier;
use App\Domain\Exception\InvalidEmailAddress;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\UserRepository;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use OpenApi\Attributes as OA;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ReadUserController
 *
 * @package App\UserInterface\UserManagement
 */
final class ReadUserController extends AbstractController implements AuthenticationAwareController
{
    use AuthenticationAwareMethods;

    public function __construct(
        private readonly DocumentEncoder $documentEncoder,
        private readonly UserRepository $users
    ) {
    }

    #[
        OA\Get(
            path: "/users/me",
            operationId: "readCurrentUser",
            description: "This endpoint displays the information of the user that is present in the requesting
    Bearer token in authorization header, displaying it only this token is valid.",
            summary: "Authenticated user",
            security: [["OAuth2" => ["forum"]]],
            tags: ["User Management"]
        ),
        OA\Response(
            response: 200,
            description: "User resource document",
            content: new OA\MediaType(
                mediaType: "application/vnd.api+json",
                schema: new OA\Schema(ref: '#/components/schemas/UserDocument')
            )
        ),
        OA\Response(
            response: 401,
            description: "Unauthorized",
            content: new OA\MediaType(
                mediaType: "application/vnd.api+json",
                schema: new OA\Schema(ref: "#/components/schemas/jsonApiError401")
            )
        ),
    ]
    #[Route(path: "/users/me")]
    public function me(): Response
    {
        return new Response(
            content: $this->documentEncoder->encode($this->user()),
            headers: ['content-type' => "application/vnd.api+json"]
        );
    }

    #[
        OA\Get(
            path: "/users/{userIdOrEmail}",
            operationId: "readUser",
            description: "This endpoint displays the information of the user that has the provided identifier or email address.",
            summary: "Read user by ID/Email",
            security: [["OAuth2" => ["forum"]]],
            tags: ["User Management"]
        ),
        OA\Response(
            response: 200,
            description: "User resource document",
            content: new OA\MediaType(
                mediaType: "application/vnd.api+json",
                schema: new OA\Schema(ref: '#/components/schemas/UserDocument')
            )
        ),
        OA\Response(
            response: 401,
            description: "Unauthorized",
            content: new OA\MediaType(
                mediaType: "application/vnd.api+json",
                schema: new OA\Schema(ref: "#/components/schemas/jsonApiError401")
            )
        ),
        OA\Response(
            response: 404,
            description: "Not found",
            content: new OA\MediaType(
                mediaType: "application/vnd.api+json",
                schema: new OA\Schema(ref: "#/components/schemas/entityNotFound")
            )
        ),
    ]
    #[Route(path: "/users/{userId}")]
    public function read(string $userId): Response
    {
        try {
            $user = $this->loadByEmail($userId);
        } catch (InvalidEmailAddress) {
            $user = $this->loadById($userId);
        }

        return new Response(
            content: $this->documentEncoder->encode($user),
            headers: ['content-type' => "application/vnd.api+json"]
        );
    }

    /**
     * loadByEmail
     *
     * @param string $emailString
     * @return User
     * @throws EntityNotFound|InvalidEmailAddress
     */
    private function loadByEmail(string $emailString): User
    {
        $email = new Email($emailString);
        return $this->users->withEmail($email);
    }

    /**
     * loadById
     *
     * @param string $userId
     * @return User
     * @throws EntityNotFound|InvalidAggregateIdentifier
     */
    private function loadById(string $userId): User
    {
        $userId = new User\UserId($userId);
        return $this->users->withId($userId);
    }
}