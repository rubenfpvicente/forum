<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth;

use App\Domain\Exception\EntityNotFound;
use App\Domain\OAuth\Scope;
use App\Domain\OAuth\Scope\ScopeId;
use App\Domain\OAuth\ScopeRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * DoctrineScopeRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\OAuth
 */
final class DoctrineScopeRepository implements ScopeRepository
{

    /**
     * Creates a DoctrineScopeRepository
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function add(Scope $scope): Scope
    {
        $this->entityManager->persist($scope);
        return $scope;
    }

    /**
     * @inheritDoc
     */
    public function withId(ScopeId $scopeId): Scope
    {
        $scope = $this->entityManager->find(Scope::class, $scopeId);
        if ($scope instanceof Scope) {
            return $scope;
        }

        throw new EntityNotFound("Cannot find a scope with ID: $scopeId");
    }

    /**
     * @inheritDoc
     */
    public function getScopeEntityByIdentifier($identifier): ScopeEntityInterface|Scope|null
    {
        try {
            $scope = $this->withId(new ScopeId($identifier));
        } catch (EntityNotFound) {
            return null;
        }

        return $scope;
    }

    /**
     * @inheritDoc
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {
        return $scopes;
    }
}
