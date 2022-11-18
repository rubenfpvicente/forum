<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\UserManagement;

use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

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
}
