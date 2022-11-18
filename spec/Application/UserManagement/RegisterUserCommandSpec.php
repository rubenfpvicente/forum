<?php

namespace spec\App\Application\UserManagement;

use App\Application\Command;
use App\Application\UserManagement\RegisterUserCommand;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\Password;
use PhpSpec\ObjectBehavior;

/**
 * RegisterUserCommandSpec
 *
 * @package spec\App\Application\UserManagement
 */
class RegisterUserCommandSpec extends ObjectBehavior
{

    private $name;
    private $email;
    private $password;

    function let()
    {
        $this->name = 'John Doe';
        $this->email = new Email('john.doe@example.com');
        $this->password = new Password('secret-pass');
        $this->beConstructedWith($this->name, $this->email, $this->password);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RegisterUserCommand::class);
    }

    function ist_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
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
}
