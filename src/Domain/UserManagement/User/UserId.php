<?php

namespace App\Domain\UserManagement\User;

use App\Domain\Comparable;
use App\Domain\Exception\InvalidAggregateIdentifier;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;

/**
 * UserId
 *
 * @package App\Domain\UserManagement\User
 */
class UserId implements Stringable, Comparable, JsonSerializable
{

    private UuidInterface $uuid;

    /**
     * Creates a UserId
     *
     * @param string|null $uuidStr
     */
    public function __construct(?string $uuidStr = null)
    {
        if ($uuidStr && !Uuid::isValid($uuidStr)) {
            throw new InvalidAggregateIdentifier(
                "Provided ID is not a valid user identifier."
            );
        }
        $this->uuid = $uuidStr ? Uuid::fromString($uuidStr) : Uuid::uuid4();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @inheritDoc
     */
    public function equalsTo(mixed $other): bool
    {
        if ($other instanceof UserId) {
            return $other->uuid->equals($this->uuid);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->uuid->toString();
    }
}
