<?php

namespace spec\App\Domain\UserManagement\User;

use App\Domain\UserManagement\User\Password;
use PhpSpec\ObjectBehavior;

class PasswordSpec extends ObjectBehavior
{

    private $passStr;

    function let()
    {
        $this->passStr = 'some-secret-pass';
        $this->beConstructedWith($this->passStr);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Password::class);
    }

    function it_can_be_compared_to_a_given_string()
    {
        $this->match($this->passStr)->shouldBe(true);
        $this->match('other-pass')->shouldBe(false);
    }

    function it_can_created_a_generated_password()
    {
        $this->beConstructedWith();
        $this->match('')->shouldBe(false);
    }

    function it_can_be_converted_to_string()
    {
        $this->shouldBeAnInstanceOf(\Stringable::class);
        $this->__toString()->shouldMatch(Password::HASH_REGEX);
    }

    function it_can_be_created_from_an_hash()
    {
        $password = 'secret-pass';
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $this->beConstructedWith($hash);
        $this->match($password)->shouldBe(true);
        $this->__toString()->shouldBe($hash);
    }
}
