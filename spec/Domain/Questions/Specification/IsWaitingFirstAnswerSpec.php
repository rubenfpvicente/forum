<?php

namespace spec\App\Domain\Questions\Specification;

use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionSpecification;
use App\Domain\Questions\Specification\IsWaitingFirstAnswer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

class IsWaitingFirstAnswerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(IsWaitingFirstAnswer::class);
    }

    function its_a_question_specification()
    {
        $this->shouldBeAnInstanceOf(QuestionSpecification::class);
    }

    function it_is_satisfied_when_question_has_no_answer(
        Question $question
    ) {
        $question->answers()->willReturn(new ArrayCollection());
        $this->isSatisfiedBy($question)->shouldBe(true);
    }

    function it_fails_when_question_has_at_least_one_answer(
        Question $question
    ) {
        $question->answers()->willReturn(new ArrayCollection(['test']));
        $this->isSatisfiedBy($question)->shouldBe(false);
    }
}
