<?php

namespace App\Domain\OAuth\Events;

use App\Domain\AbstractEvent;
use App\Domain\OAuth\Scope\ScopeId;
use JsonSerializable;

/**
 * ScopeWasCreated
 *
 * @package App\Domain\OAuth\Events
 */
class ScopeWasCreated extends AbstractEvent implements JsonSerializable
{
    /**
     * Creates a ScopeWasCreated
     *
     * @param ScopeId $scopeId
     * @param string $description
     */
    public function __construct(
        private readonly ScopeId $scopeId,
        private readonly string $description
    ) {
        parent::__construct();
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
    public function jsonSerialize(): array
    {
        return [
            'scopeId' => $this->scopeId,
            'description' => $this->description
        ];
    }
}
