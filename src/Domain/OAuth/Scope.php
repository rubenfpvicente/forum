<?php

namespace App\Domain\OAuth;

use App\Domain\OAuth\Events\ScopeWasCreated;
use App\Domain\OAuth\Scope\ScopeId;
use App\Domain\RootAggregate;
use App\Domain\RootAggregateMethods;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Scope
 *
 * @package App\Domain\OAuth
 */
#[
    Entity,
    Table(name: "scopes")
]
class Scope implements RootAggregate, ScopeEntityInterface
{
    use RootAggregateMethods;

    /**
     * Creates a Scope
     *
     * @param ScopeId $scopeId
     * @param string $description
     */
    public function __construct(
        #[
            Id,
            GeneratedValue(strategy: 'NONE'),
            Column(name: "id", type: "ScopeId")
        ]
        private ScopeId $scopeId,

        #[Column]
        private string $description
    ) {
        $this->recordThat(new ScopeWasCreated($this->scopeId, $this->description));
    }

    /**
     * scopeId
     *
     * @return ScopeId
     */
    public function scopeId(): ScopeId
    {
        return $this->scopeId;
    }

    /**
     * description
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return (string) $this->scopeId();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->scopeId;
    }
}
