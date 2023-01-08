<?php

namespace spec\App\Application\Answers;

use App\Application\Answers\Events\AnswerWasPlaced;
use App\Application\Answers\PlaceAnswerCommand;
use App\Application\Answers\PlaceAnswerHandler;
use App\Domain\Answers\Answer;
use App\Domain\Answers\AnswerRepository;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

class PlaceAnswerHandlerSpec extends ObjectBehavior
{
    private $userId;
    private $questionId;

    function let(
        AnswerRepository $answers,
        UserRepository $users,
        User $owner,
        QuestionRepository $questions,
        Question $question,
        EventDispatcherInterface $dispatcher
    ) {

        $this->userId = new User\UserId();
        $users->withId($this->userId)->willReturn($owner);
        $owner->userId()->willReturn($this->userId);

        $this->questionId = new Question\QuestionId();
        $questions->withQuestionId($this->questionId)->willReturn($question);
        $question->questionId()->willReturn($this->questionId);

        $answers->add(Argument::type(Answer::class))->willReturnArgument();

        $dispatcher->dispatch(Argument::type(AnswerWasPlaced::class))->willReturnArgument();

        $this->beConstructedWith($answers, $users, $questions, $dispatcher);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(PlaceAnswerHandler::class);
    }

    function it_handles_place_answer_command(
        AnswerRepository $answers,
        EventDispatcherInterface $dispatcher
    ) {
        $command = new PlaceAnswerCommand(
            $this->userId,
            $this->questionId,
            'A body'
        );

        $answer = $this->handle($command);
        $answer->shouldBeAnInstanceOf(Answer::class);

        $answers->add($answer)->shouldHaveBeenCalled();
        $dispatcher->dispatch(Argument::type(AnswerWasPlaced::class))->shouldHaveBeenCalled();
    }
}
