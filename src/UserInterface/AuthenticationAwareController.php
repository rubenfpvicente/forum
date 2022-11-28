<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface;

use App\Domain\UserManagement\User;

/**
 * AuthenticationAwareController
 *
 * @package App\UserInterface
 */
interface AuthenticationAwareController
{

    /**
     * Current logged in user
     *
     * @return User|null
     */
    public function user(): ?User;

    /**
     * Injects user into controller
     *
     * @param User $user
     */
    public function withUser(User $user): void;
}
