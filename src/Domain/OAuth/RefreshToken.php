<?php

namespace App\Domain\OAuth;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use Ramsey\Uuid\Uuid;

/**
 * RefreshToken
 *
 * @package App\Domain\OAuth
 */
#[
    Entity,
    Table(name: "refresh_tokens")
]
class RefreshToken implements RefreshTokenEntityInterface
{

    /**
     * @var string
     */
    #[
        Id,
        GeneratedValue(strategy: 'NONE'),
        Column(name: "id", type: "string")
    ]
    protected $identifier;

    /**
     * @var AccessTokenEntityInterface
     */
    #[
        OneToOne(targetEntity: AccessToken::class),
        JoinColumn(name: "access_token_id", referencedColumnName: "id")
    ]
    protected $accessToken;

    /**
     * @var DateTimeImmutable
     */
    #[Column(type: "datetime_immutable")]
    protected $expiryDateTime;

    /**
     * Creates a RefreshToken
     *
     */
    public function __construct()
    {
        $this->identifier = Uuid::uuid4()->toString();
    }

    use EntityTrait;
    use RefreshTokenTrait;
}
