<?php

namespace App\Domain\UserManagement\User;

use App\Domain\Comparable;
use App\Domain\Exception\InvalidEmailAddress;
use JsonSerializable;
use Stringable;

/**
 * Email
 *
 * @package App\Domain\UserManagement\User
 */
class Email implements JsonSerializable, Stringable, Comparable
{
    /**
     * Creates a Email
     *
     * @param string $email
     */
    public function __construct(private string $email)
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddress("'$this->email' is not a valid email address.");
        }
    }

    /**
     * @inheritDoc
     */
    public function equalsTo(mixed $other): bool
    {
        if ($other instanceof Email) {
            return $other->email === $this->email;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->email;
    }
}
