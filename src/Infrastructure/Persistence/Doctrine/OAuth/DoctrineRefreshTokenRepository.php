<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth;

use App\Domain\OAuth\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * DoctrineRefreshTokenRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\OAuth
 */
final class DoctrineRefreshTokenRepository implements RefreshTokenRepositoryInterface
{

    /**
     * Creates a DoctrineRefreshTokenRepository
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    /**
     * @inheritDoc
     */
    public function getNewRefreshToken(): RefreshToken|RefreshTokenEntityInterface|null
    {
        return new RefreshToken();
    }

    /**
     * @inheritDoc
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->entityManager->persist($refreshTokenEntity);
    }

    /**
     * @inheritDoc
     */
    public function revokeRefreshToken($tokenId)
    {
        $token = $this->entityManager->find(RefreshToken::class, $tokenId);
        if ($token instanceof RefreshToken) {
            $this->entityManager->remove($token);
        }
    }

    /**
     * @inheritDoc
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        $token = $this->entityManager->find(RefreshToken::class, $tokenId);
        return is_null($token);
    }
}
