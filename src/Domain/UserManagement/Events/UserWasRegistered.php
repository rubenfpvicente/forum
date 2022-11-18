<?php

namespace App\Domain\UserManagement\Events;

use App\Domain\AbstractEvent;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\UserId;
use JsonSerializable;

/**
 * UserWasRegistered
 *
 * @package App\Domain\UserManagement\Events
 */
class UserWasRegistered extends AbstractEvent implements JsonSerializable
{

    /**
     * Creates a UserWasRegistered
     *
     * @param UserId $userId
     * @param string $name
     * @param Email $email
     * @param string $passwordHash
     */
    public function __construct(
        private readonly UserId $userId,
        private readonly string $name,
        private readonly Email $email,
        private readonly string $passwordHash
    ) {
        parent::__construct();
    }

    /**
     * userId
     *
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * email
     *
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }

    /**
     * passwordHash
     *
     * @return string
     */
    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'passwordHash' => $this->passwordHash
        ];
    }
}
