<?php

namespace spec\App\Domain\Answers;

use App\Domain\Answers\Answer;
use App\Domain\Questions\Question;
use App\Domain\RootAggregate;
use App\Domain\UserManagement\User;
use PhpSpec\ObjectBehavior;

class AnswerSpec extends ObjectBehavior
{
    private $body;

    function let(User $owner, Question $question)
    {
        $owner->userId()->willReturn(new User\UserId());
        $question->questionId()->willReturn(new Question\QuestionId());

        $this->body = 'And a body';
        $this->beConstructedWith($owner, $question, $this->body);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Answer::class);
    }


    function it_has_a_answer_id()
    {
        $this->answerId()->shouldBeAnInstanceOf(Answer\AnswerId::class);
    }
}
