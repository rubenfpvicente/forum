<?php

namespace App\Domain\OAuth\Client;

use App\Domain\Comparable;
use JsonSerializable;
use Stringable;

/**
 * ClientId
 *
 * @package App\Domain\OAuth\Client
 */
class ClientId implements Stringable, Comparable, JsonSerializable
{
    /**
     * Creates a ClientId
     *
     * @param string $identifier
     */
    public function __construct(private readonly string $identifier)
    {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function equalsTo(mixed $other): bool
    {
        if ($other instanceof ClientId) {
            return $other->identifier === $this->identifier;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->identifier;
    }
}
