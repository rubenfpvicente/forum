<?php

namespace spec\App\Domain\UserManagement\User;

use App\Domain\Comparable;
use App\Domain\Exception\InvalidEmailAddress;
use App\Domain\UserManagement\User\Email;
use PhpSpec\ObjectBehavior;

class EmailSpec extends ObjectBehavior
{

    private $emailStr;

    function let()
    {
        $this->emailStr = 'john.doe@example.com';
        $this->beConstructedWith($this->emailStr);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Email::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->shouldBeAnInstanceOf(\Stringable::class);
        $this->__toString()->shouldBe($this->emailStr);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe($this->emailStr);
    }

    function it_can_be_compared()
    {
        $this->shouldBeAnInstanceOf(Comparable::class);
        $this->equalsTo(new Email($this->emailStr))->shouldBe(true);
        $this->equalsTo(new Email('jane.doe@example.com'))->shouldBe(false);
        $this->equalsTo($this->emailStr)->shouldBe(false);
    }

    function it_only_creates_with_valid_email_addresses()
    {
        $this->beConstructedWith('Invalid email');
        $this->shouldThrow(InvalidEmailAddress::class)
            ->duringInstantiation();
    }
}
