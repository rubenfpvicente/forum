<?php

namespace App\Domain\OAuth;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Lcobucci\JWT\Configuration;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use Ramsey\Uuid\Uuid;

/**
 * AccessToken
 *
 * @package App\Domain\OAuth
 */
#[
    Entity,
    Table(name: "access_tokens")
]
class AccessToken implements AccessTokenEntityInterface
{

    use EntityTrait;
    use TokenEntityTrait;
    use AccessTokenTrait;

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
     * @var ScopeEntityInterface[]
     */
    #[
        ManyToMany(targetEntity: Scope::class, fetch: "EAGER"),
        JoinTable(name: "scope_tokens"),
        JoinColumn(name: "token_id", referencedColumnName: "id"),
        InverseJoinColumn(name: "scope_id", referencedColumnName: "id")
    ]
    protected $scopes = [];

    /**
     * @var DateTimeImmutable
     */
    #[Column(type: "datetime_immutable")]
    protected $expiryDateTime;

    /**
     * @var string|int|null
     */
    #[Column]
    protected $userIdentifier;

    /**
     * @var ClientEntityInterface
     */
    #[
        ManyToOne(targetEntity: Client::class, cascade: ['all'], fetch: "EAGER"),
        JoinColumn(name: "client_id")
    ]
    protected $client;

    /**
     * @var CryptKey
     */
    private $privateKey;

    /**
     * @var Configuration
     */
    private $jwtConfiguration;

    public function __construct(
    ) {
        $this->identifier = Uuid::uuid4()->toString();
    }

    /**
     * Return an array of scopes associated with the token.
     *
     * @return ScopeEntityInterface[]
     */
    public function getScopes(): array
    {
        if ($this->scopes instanceof Collection) {
            $this->scopes = $this->scopes->toArray();
        }

        return array_values($this->scopes);
    }
}
