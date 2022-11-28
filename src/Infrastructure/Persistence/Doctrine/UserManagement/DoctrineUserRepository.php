<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\UserManagement;

use App\Domain\Exception\EntityNotFound;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\UserId;
use App\Domain\UserManagement\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * DoctrineUserRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\UserManagement
 */
final class DoctrineUserRepository implements UserRepository
{
    /**
     * Creates a DoctrineUserRepository
     *
     * @param EntityManager $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function add(User $user): User
    {
        $this->entityManager->persist($user);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): User|UserEntityInterface|null
    {
        try {
            $user = $this->withEmail(new Email($username));
        } catch (EntityNotFound) {
            return null;
        }
        return $user->password()->match($password) ? $user : null;
    }

    /**
     * @inheritDoc
     */
    public function withId(UserId $userId): User
    {
        $user = $this->entityManager->find(User::class, $userId);
        if ($user instanceof User) {
            return $user;
        }

        throw new EntityNotFound("Cannot find user with provided identifier.");
    }

    /**
     * @inheritDoc
     */
    public function withEmail(Email $email): User
    {
        $repo = $this->entityManager->getRepository(User::class);
        $user = $repo->findOneBy(['email' => $email]);
        if ($user instanceof User) {
            return $user;
        }

        throw new EntityNotFound("Cannot find user with provided email address.");
    }
}
