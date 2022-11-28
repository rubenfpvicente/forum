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
 * AuthenticationAwareMethods trait
 *
 * @package App\UserInterface
 */
trait AuthenticationAwareMethods
{

    protected ?User $user = null;

    /**
     * @inheritDoc
     */
    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function withUser(User $user): void
    {
        $this->user = $user;
    }
}