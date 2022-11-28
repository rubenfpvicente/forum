<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth;

use App\Domain\OAuth\AccessToken;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * DoctrineAccessTokenRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\OAuth
 */
final class DoctrineAccessTokenRepository implements AccessTokenRepositoryInterface
{

    /**
     * Creates a DoctrineAccessTokenRepository
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $token = new AccessToken();
        $token->setClient($clientEntity);
        $token->setUserIdentifier($userIdentifier);
        foreach ($scopes as $scope) {
            $token->addScope($scope);
        }
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->entityManager->persist($accessTokenEntity);
    }

    /**
     * @inheritDoc
     */
    public function revokeAccessToken($tokenId)
    {
        $accessToken = $this->entityManager->find(AccessToken::class, $tokenId);
        if ($accessToken instanceof AccessToken) {
            $this->entityManager->remove($accessToken);
        }
    }

    /**
     * @inheritDoc
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        $accessToken = $this->entityManager->find(AccessToken::class, $tokenId);
        return is_null($accessToken);
    }
}
