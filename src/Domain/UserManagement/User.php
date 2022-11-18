<?php

namespace App\Domain\UserManagement;

use App\Domain\RootAggregate;
use App\Domain\RootAggregateMethods;
use App\Domain\UserManagement\Events\UserWasRegistered;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\Password;
use App\Domain\UserManagement\User\UserId;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

/**
 * User
 *
 * @package App\Domain\UserManagement
 */
#[Entity]
#[Table(name: "users")]
#[Index(fields: ["email"], name: "emailIdx")]
class User implements RootAggregate
{

    use RootAggregateMethods;

    #[Id]
    #[GeneratedValue(strategy: 'NONE')]
    #[Column(name: 'id', type: 'UserId')]
    private UserId $userId;

    /**
     * Creates a User
     *
     * @param string $name
     * @param Email $email
     * @param Password|null $password
     */
    public function __construct(
        #[Column]
        private string $name,
        #[Column(type: 'Email')]
        private Email $email,
        #[Column(type: 'Password')]
        private ?Password $password = null
    ) {
        $this->userId = new UserId();
        $this->password = $this->password ?: new Password();
        $this->recordThat(new UserWasRegistered(
            $this->userId,
            $this->name,
            $this->email,
            (string) $this->password
        ));
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
     * password
     *
     * @return Password
     */
    public function password(): Password
    {
        return $this->password;
    }
}
