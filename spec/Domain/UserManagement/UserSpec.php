<?php

namespace spec\App\Domain\UserManagement;

use App\Domain\RootAggregate;
use App\Domain\UserManagement\Events\UserWasRegistered;
use App\Domain\UserManagement\User;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{

    private $name;
    private $email;
    private $password;
    private $passwordStr;

    function let()
    {
        $this->name = 'John Doe';
        $this->email = new User\Email('john.doe@example.com');
        $this->passwordStr = 'secret';
        $this->password = new User\Password($this->passwordStr);
        $this->beConstructedWith($this->name, $this->email, $this->password);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_has_a_userId()
    {
        $this->userId()->shouldBeAnInstanceOf(User\UserId::class);
    }

    function it_has_a_name()
    {
        $this->name()->shouldBe($this->name);
    }

    function it_has_a_email()
    {
        $this->email()->shouldBe($this->email);
    }

    function it_has_a_password()
    {
        $this->password()->shouldBe($this->password);
    }

    function it_can_be_created_without_a_password()
    {
        $this->beConstructedWith($this->name, $this->email);
        $this->password()->match($this->passwordStr)->shouldBe(false);
        $this->name()->shouldBe($this->name);
        $this->email()->shouldBe($this->email);
        $this->password()->shouldNotBe($this->password);
    }

    function its_a_root_aggregate()
    {
        $this->shouldBeAnInstanceOf(RootAggregate::class);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(UserWasRegistered::class);
    }
}
