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
 * UserIdentifier
 *
 * @package App\Domain\UserManagement
 */
interface UserIdentifier
{

    /**
     * Returns current logged in user
     *
     * @return null|User
     */
    public function currentUser(): ?User;

    /**
     * Set current logged in user
     * @param User $user
     */
    public function withUser(User $user): void;
}
