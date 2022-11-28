<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Domain\OAuth;

use App\Domain\Exception\EntityNotFound;
use App\Domain\OAuth\Scope\ScopeId;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * ScopeRepository
 *
 * @package App\Domain\OAuth
 */
interface ScopeRepository extends ScopeRepositoryInterface
{

    /**
     * Adds a scope to the repository
     *
     * @param Scope $scope
     * @return Scope
     */
    public function add(Scope $scope): Scope;

    /**
     * Retrieves the scope saved with provided identifier
     *
     * @param ScopeId $scopeId
     * @return Scope
     *
     * @throws \RuntimeException|EntityNotFound
     */
    public function withId(ScopeId $scopeId): Scope;
}
