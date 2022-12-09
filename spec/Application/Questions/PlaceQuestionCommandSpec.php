<?php

namespace spec\App\Application\Questions;

use App\Application\Command;
use App\Application\Questions\PlaceQuestionCommand;
use App\Domain\UserManagement\User\UserId;
use PhpSpec\ObjectBehavior;

class PlaceQuestionCommandSpec extends ObjectBehavior
{

    private $ownerUserId;
    private $title;
    private $body;

    function let()
    {
        $this->ownerUserId = new UserId();
        $this->title = 'Title';
        $this->body = 'Some text as body...';
        $this->beConstructedWith(
            $this->ownerUserId,
            $this->title,
            $this->body
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PlaceQuestionCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    function it_has_a_ownerUserId()
    {
        $this->ownerUserId()->shouldBe($this->ownerUserId);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBe($this->title);
    }

    function it_has_a_body()
    {
        $this->body()->shouldBe($this->body);
    }
}
