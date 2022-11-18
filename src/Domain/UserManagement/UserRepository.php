<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\Domain\UserManagement;

/**
 * UserRepository
 *
 * @package App\Domain\UserManagement
 */
interface UserRepository
{

    /**
     * Adds a user to the repository
     *
     * @param User $user
     * @return User
     */
    public function add(User $user): User;
}