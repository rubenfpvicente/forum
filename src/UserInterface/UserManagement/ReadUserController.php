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

    #[Route(path: "/users/me")]
    public function me(): Response
    {
        return new Response(
            content: $this->documentEncoder->encode($this->user()),
            headers: ['content-type' => "application/vnd.api+json"]
        );
    }


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