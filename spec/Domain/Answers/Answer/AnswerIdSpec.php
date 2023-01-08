<?php

namespace spec\App\Domain\Answers\Answer;

use App\Domain\Answers\Answer\AnswerId;
use App\Domain\Exception\InvalidAggregateIdentifier;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class AnswerIdSpec extends ObjectBehavior
{
    private $uuidStr;

    function let()
    {
        $this->uuidStr = Uuid::uuid4()->toString();
        $this->beConstructedWith($this->uuidStr);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnswerId::class);
    }

    function it_can_be_converted_to_string()
    {
        $this->shouldBeAnInstanceOf(\Stringable::class);
        $this->__toString()->shouldBe($this->uuidStr);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe($this->uuidStr);
    }

    function it_throws_an_exception_when_id_is_nopt_a_valid_uuid()
    {
        $this->beConstructedWith('some-fullish-identifier');
        $this->shouldThrow(InvalidAggregateIdentifier::class)
            ->duringInstantiation();
    }

    function it_can_generate_an_uuid_when_no_argument_is_passed_to_constructor()
    {
        $this->beConstructedWith();
        $this->__toString()->shouldMatch('/[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89aAbB][a-f0-9]{3}-[a-f0-9]{12}/');
    }
}
