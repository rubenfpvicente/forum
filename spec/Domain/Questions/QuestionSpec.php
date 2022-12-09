<?php

namespace spec\App\Domain\Questions;

use App\Domain\Questions\Events\QuestionWasPlaced;
use App\Domain\Questions\Question;
use App\Domain\RootAggregate;
use App\Domain\UserManagement\User;
use PhpSpec\ObjectBehavior;

class QuestionSpec extends ObjectBehavior
{

    private $title;
    private $body;

    function let(User $owner)
    {
        $owner->userId()->willReturn(new User\UserId());

        $this->title = 'Some title';
        $this->body = 'And a body';
        $this->beConstructedWith($owner, $this->title, $this->body);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Question::class);
    }

    function its_a_root_aggregate()
    {
        $this->shouldBeAnInstanceOf(RootAggregate::class);
        $events = $this->releaseEvents();
        $events->shouldHaveCount(1);
        $events[0]->shouldBeAnInstanceOf(QuestionWasPlaced::class);
    }

    function it_has_a_question_id()
    {
        $this->questionId()->shouldBeAnInstanceOf(Question\QuestionId::class);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBe($this->title);
    }

    function it_has_a_body()
    {
        $this->body()->shouldBe($this->body);
    }

    function it_has_a_owner(User $owner)
    {
        $this->owner()->shouldBe($owner);
    }

    function it_has_a_closed_state()
    {
        $this->isClosed()->shouldBe(false);
    }

    function it_has_an_archived_state()
    {
        $this->isArchived()->shouldBe(false);
    }

    function it_can_be_converted_to_json(User $owner)
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'questionId' => $this->questionId(),
            'title' => $this->title,
            'body' => $this->body,
            'owner' => $owner,
            'archived' => false,
            'closed' => false
        ]);
    }
}
