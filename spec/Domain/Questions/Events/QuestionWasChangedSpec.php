<?php

namespace spec\App\Domain\Questions\Events;

use App\Domain\AbstractEvent;
use App\Domain\Questions\Events\QuestionWasChanged;
use App\Domain\Questions\Question\QuestionId;
use PhpSpec\ObjectBehavior;
use Symfony\Contracts\EventDispatcher\Event;

class QuestionWasChangedSpec extends ObjectBehavior
{

    private $questionId;
    private $title;
    private $body;

    function let()
    {
        $this->questionId = new QuestionId();
        $this->title = 'title';
        $this->body = 'body';
        $this->beConstructedWith($this->questionId, $this->title, $this->body);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QuestionWasChanged::class);
    }

    function its_an_event()
    {
        $this->shouldBeAnInstanceOf(Event::class);
        $this->shouldHaveType(AbstractEvent::class);
        $this->occurredOn()->shouldBeAnInstanceOf(\DateTimeImmutable::class);
    }


    function it_has_a_questionId()
    {
        $this->questionId()->shouldBe($this->questionId);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBe($this->title);
    }

    function it_has_a_body()
    {
        $this->body()->shouldBe($this->body);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'questionId' => $this->questionId,
            'title' => $this->title,
            'body' => $this->body
        ]);
    }
}
