<?php

namespace App\Domain\OAuth\Scope;

use App\Domain\Comparable;
use JsonSerializable;
use Stringable;

/**
 * ScopeId
 *
 * @package App\Domain\OAuth\Scope
 */
class ScopeId implements Stringable, Comparable, JsonSerializable
{

    /**
     * Creates a ScopeId
     *
     * @param string $scopeIdentifier
     */
    public function __construct(private readonly string $scopeIdentifier)
    {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->scopeIdentifier;
    }

    /**
     * @inheritDoc
     */
    public function equalsTo(mixed $other): bool
    {
        if ($other instanceof ScopeId) {
            return $other->scopeIdentifier === $this->scopeIdentifier;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->scopeIdentifier;
    }
}
