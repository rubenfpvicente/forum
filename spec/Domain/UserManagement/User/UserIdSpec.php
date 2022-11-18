<?php

namespace spec\App\Domain\UserManagement\User;

use App\Domain\Comparable;
use App\Domain\Exception\InvalidAggregateIdentifier;
use App\Domain\UserManagement\User\UserId;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class UserIdSpec extends ObjectBehavior
{

    private $uuidStr;

    function let()
    {
        $this->uuidStr = Uuid::uuid4()->toString();
        $this->beConstructedWith($this->uuidStr);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserId::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->shouldBeAnInstanceOf(\Stringable::class);
        $this->__toString()->shouldBe($this->uuidStr);
    }

    function it_can_be_compared()
    {
        $this->shouldBeAnInstanceOf(Comparable::class);
        $this->equalsTo(new UserId($this->uuidStr))->shouldBe(true);
        $this->equalsTo(new UserId())->shouldBe(false);
        $this->equalsTo($this->uuidStr)->shouldBe(false);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe($this->uuidStr);
    }

    function it_can_be_created_without_a_uuid_string()
    {
        $this->beConstructedWith();
        $this->shouldBeAnInstanceOf(UserId::class);
    }

    function it_throws_an_exception_when_uuid_is_not_valid()
    {
        $this->beConstructedWith('Some strange UUID.');
        $this->shouldThrow(InvalidAggregateIdentifier::class)
            ->duringInstantiation();
    }
}
