<?php

namespace spec\App\Domain\UserManagement\Events;

use App\Domain\UserManagement\Events\UserWasRegistered;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\UserId;
use DateTimeImmutable;
use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;
use JsonSerializable;
use PhpSpec\ObjectBehavior;

class UserWasRegisteredSpec extends ObjectBehavior
{
    private $userId;
    private $name;
    private $email;
    private $passwordHash;

    function let()
    {
        $this->userId = new UserId();
        $this->name = 'John Doe';
        $this->email = new Email('john.doe@example.com');
        $this->passwordHash = password_hash('secret-pass', PASSWORD_ARGON2ID);
        $this->beConstructedWith(
            $this->userId,
            $this->name,
            $this->email,
            $this->passwordHash
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserWasRegistered::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(DomainEvent::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }

    function it_has_a_user_id()
    {
        $this->userId()->shouldBe($this->userId);
    }

    function it_has_a_name()
    {
        $this->name()->shouldBe($this->name);
    }

    function it_has_a_email()
    {
        $this->email()->shouldBe($this->email);
    }

    function it_has_a_password_hash()
    {
        $this->passwordHash()->shouldBe($this->passwordHash);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'passwordHash' => $this->passwordHash
        ]);
    }
}
