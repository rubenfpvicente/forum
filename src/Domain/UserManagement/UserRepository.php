<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\Domain\UserManagement;

use App\Domain\Exception\EntityNotFound;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\UserId;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * UserRepository
 *
 * @package App\Domain\UserManagement
 */
interface UserRepository extends UserRepositoryInterface
{

    /**
     * Adds a user to the repository
     *
     * @param User $user
     * @return User
     */
    public function add(User $user): User;

    /**
     * Retrieves the user added with provided identifier
     *
     * @param UserId $userId
     * @return User
     *
     * @throws \RuntimeException|EntityNotFound
     */
    public function withId(UserId $userId): User;

    /**
     * Retrieves the user added with provided email
     *
     * @param Email $email
     * @return User
     *
     * @throws \RuntimeException|EntityNotFound
     */
    public function withEmail(Email $email): User;
}
