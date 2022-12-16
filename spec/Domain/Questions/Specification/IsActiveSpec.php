<?php

namespace spec\App\Domain\Questions\Specification;

use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionSpecification;
use App\Domain\Questions\Specification\IsActive;
use PhpSpec\ObjectBehavior;

class IsActiveSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IsActive::class);
    }

    function it_a_question_specification()
    {
        $this->shouldBeAnInstanceOf(QuestionSpecification::class);
    }

    function it_validates_question_is_not_open_nor_archived(
        Question $question
    ) {
        $question->isArchived()->willReturn(false);
        $question->isClosed()->willReturn(false);
        $this->isSatisfiedBy($question)->shouldBe(true);
    }

    function it_fails_when_question_is_closed(
        Question $question
    ) {
        $question->isArchived()->willReturn(false);
        $question->isClosed()->willReturn(true);
        $this->isSatisfiedBy($question)->shouldBe(false);
    }

    function it_fails_when_question_is_archived(
        Question $question
    ) {
        $question->isArchived()->willReturn(true);
        $question->isClosed()->willReturn(false);
        $this->isSatisfiedBy($question)->shouldBe(false);
    }
}
