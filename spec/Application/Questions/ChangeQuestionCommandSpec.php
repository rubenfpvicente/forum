<?php

namespace spec\App\Application\Questions;

use App\Application\Command;
use App\Application\Questions\ChangeQuestionCommand;
use App\Domain\Questions\Question\QuestionId;
use PhpSpec\ObjectBehavior;

class ChangeQuestionCommandSpec extends ObjectBehavior
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
        $this->shouldHaveType(ChangeQuestionCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
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
}
