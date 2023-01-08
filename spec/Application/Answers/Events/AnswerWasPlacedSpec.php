<?php

namespace spec\App\Application\Answers\Events;

use App\Application\Answers\Events\AnswerWasPlaced;
use App\Domain\AbstractEvent;
use App\Domain\Answers\Answer\AnswerId;
use App\Domain\DomainEvent;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\UserManagement\User\UserId;
use DateTimeImmutable;
use JsonSerializable;
use PhpSpec\ObjectBehavior;

class AnswerWasPlacedSpec extends ObjectBehavior
{
    private $ownerUserId;
    private $questionId;
    private $answerId;
    private $body;

    function let()
    {
        $this->ownerUserId = new UserId();
        $this->questionId = new QuestionId();
        $this->answerId = new AnswerId();
        $this->body = 'A long text as body...';
        $this->beConstructedWith(
            $this->ownerUserId,
            $this->questionId,
            $this->answerId,
            $this->body,
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AnswerWasPlaced::class);
    }

    function it_has_a_ownerUserId()
    {
        $this->ownerUserId()->shouldBe($this->ownerUserId);
    }

    function it_has_a_questionId()
    {
        $this->questionId()->shouldBe($this->questionId);
    }

    function it_has_a_answerId()
    {
        $this->answerId()->shouldBe($this->answerId);
    }

    function it_has_a_body()
    {
        $this->body()->shouldBe($this->body);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(DomainEvent::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(DateTimeImmutable::class);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'ownerUserId' => $this->ownerUserId,
            'questionId' => $this->questionId,
            'answerId' => $this->answerId,
            'body' => $this->body,
        ]);
    }
}