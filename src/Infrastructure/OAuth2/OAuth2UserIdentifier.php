<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\OAuth2;

use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserIdentifier;

/**
 * OAuth2UserIdentifier
 *
 * @package App\Infrastructure\OAuth2
 */
final class OAuth2UserIdentifier implements UserIdentifier
{

    private ?User $user = null;

    /**
     * @inheritDoc
     */
    public function currentUser(): ?User
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
