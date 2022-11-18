<?php

namespace App\Application\UserManagement;

use App\Application\Command;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\Password;

/**
 * RegisterUserCommand
 *
 * @package App\Application\UserManagement
 */
class RegisterUserCommand implements Command
{
    /**
     * Creates a RegisterUserCommand
     *
     * @param string $name
     * @param Email $email
     * @param Password $password
     */
    public function __construct(
        private readonly string $name,
        private readonly Email $email,
        private readonly Password $password
    ) {
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
     * password
     *
     * @return Password
     */
    public function password(): Password
    {
        return $this->password;
    }
}
