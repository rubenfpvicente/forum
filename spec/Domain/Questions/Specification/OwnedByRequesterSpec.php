<?php

namespace spec\App\Domain\Questions\Specification;

use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionSpecification;
use App\Domain\Questions\Specification\OwnedByRequester;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserIdentifier;
use PhpSpec\ObjectBehavior;

class OwnedByRequesterSpec extends ObjectBehavior
{

    function let(
        UserIdentifier $identifier,
        User $loggedInUser
    ) {
        $loggedInUser->userId()->willReturn(new User\UserId());
        $identifier->currentUser()->willReturn($loggedInUser);

        $this->beConstructedWith($identifier);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OwnedByRequester::class);
    }

    function its_a_question_specification()
    {
        $this->shouldBeAnInstanceOf(QuestionSpecification::class);
    }

    function it_is_satisfied_when_question_owner_is_the_logged_in_user(
        Question $question,
        User $loggedInUser
    ) {
        $question->owner()->willReturn($loggedInUser);
        $this->isSatisfiedBy($question)->shouldBe(true);
    }

    function it_fails_when_owner_is_not_the_logged_in_user(
        Question $question,
        User $owner
    ) {
        $question->owner()->willReturn($owner);
        $owner->userId()->willReturn(new User\UserId());
        $this->isSatisfiedBy($question)->shouldBe(false);
    }
}
