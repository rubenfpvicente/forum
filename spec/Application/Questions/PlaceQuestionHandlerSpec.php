<?php

namespace spec\App\Application\Questions;

use App\Application\CommandHandler;
use App\Application\Questions\PlaceQuestionCommand;
use App\Application\Questions\PlaceQuestionHandler;
use App\Domain\Questions\Events\QuestionWasPlaced;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\UserManagement\User;
use App\Domain\UserManagement\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

class PlaceQuestionHandlerSpec extends ObjectBehavior
{
    private $userId;

    function let(
        UserRepository $users,
        User $owner,
        QuestionRepository $questions,
        EventDispatcherInterface $dispatcher
    ) {

        $this->userId = new User\UserId();
        $users->withId($this->userId)->willReturn($owner);
        $owner->userId()->willReturn($this->userId);

        $questions->add(Argument::type(Question::class))->willReturnArgument();

        $dispatcher->dispatch(Argument::type(QuestionWasPlaced::class))->willReturnArgument();

        $this->beConstructedWith($users, $questions, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PlaceQuestionHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_handles_place_question_command(
        QuestionRepository $questions,
        EventDispatcherInterface $dispatcher
    ) {
        $command = new PlaceQuestionCommand(
            $this->userId,
            'A title',
            'A body'
        );


        $question = $this->handle($command);
        $question->shouldBeAnInstanceOf(Question::class);

        $questions->add($question)->shouldHaveBeenCalled();
        $dispatcher->dispatch(Argument::type(QuestionWasPlaced::class))->shouldHaveBeenCalled();
    }
}
