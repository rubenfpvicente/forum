<?php

namespace spec\App\Domain\Questions\Events;

use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;
use App\Domain\Questions\Events\QuestionWasRemoved;
use App\Domain\Questions\Question\QuestionId;
use PhpSpec\ObjectBehavior;

class QuestionWasRemovedSpec extends ObjectBehavior
{

    private $questionId;

    function let()
    {
        $this->questionId = new QuestionId();
        $this->beConstructedWith($this->questionId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QuestionWasRemoved::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(DomainEvent::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(\DateTimeImmutable::class);
    }

    function it_has_a_questionId()
    {
        $this->questionId()->shouldBe($this->questionId);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'questionId' => $this->questionId
        ]);
    }
}
