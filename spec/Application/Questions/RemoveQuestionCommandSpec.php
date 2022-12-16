<?php

namespace spec\App\Application\Questions;

use App\Application\Command;
use App\Application\Questions\RemoveQuestionCommand;
use App\Domain\Questions\Question\QuestionId;
use PhpSpec\ObjectBehavior;

class RemoveQuestionCommandSpec extends ObjectBehavior
{

    private $questionId;

    function let()
    {
        $this->questionId = new QuestionId();
        $this->beConstructedWith($this->questionId);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveQuestionCommand::class);
    }

    function its_a_command()
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    function it_has_a_questionId()
    {
        $this->questionId()->shouldBe($this->questionId);
    }
}
