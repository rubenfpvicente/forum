<?php

namespace spec\App\Application\Questions;

use App\Application\CommandHandler;
use App\Application\Questions\RemoveQuestionCommand;
use App\Application\Questions\RemoveQuestionHandler;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Questions\Events\QuestionWasRemoved;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\Questions\Specification\IsActive;
use App\Domain\Questions\Specification\IsWaitingFirstAnswer;
use App\Domain\Questions\Specification\OwnedByRequester;
use PhpSpec\ObjectBehavior;
use Psr\EventDispatcher\EventDispatcherInterface;

class RemoveQuestionHandlerSpec extends ObjectBehavior
{

    private $questionId;

    function let(
        QuestionRepository $questions,
        IsActive $isActive,
        IsWaitingFirstAnswer $waitingFirstAnswer,
        OwnedByRequester $byRequester,
        EventDispatcherInterface $dispatcher,
        Question $question,
        QuestionWasRemoved $event
    ) {

        $this->questionId = new Question\QuestionId();
        $questions->withQuestionId($this->questionId)->willReturn($question);
        $questions->remove($question)->willReturnArgument();

        $question->releaseEvents()->willReturn([$event]);

        $isActive->isSatisfiedBy($question)->willReturn(true);
        $byRequester->isSatisfiedBy($question)->willReturn(true);
        $waitingFirstAnswer->isSatisfiedBy($question)->willReturn(true);

        $dispatcher->dispatch($event)->willReturnArgument();

        $this->beConstructedWith($questions, $isActive, $waitingFirstAnswer, $byRequester, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoveQuestionHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_handles_remove_question_command(
        QuestionRepository $questions,
        EventDispatcherInterface $dispatcher,
        Question $question,
        QuestionWasRemoved $event
    )
    {
        $command = new RemoveQuestionCommand($this->questionId);
        $this->handle($command)->shouldBe($question);
        $questions->remove($question)->shouldHaveBeenCalled();
        $dispatcher->dispatch($event)->shouldHaveBeenCalled();
    }

    function it_fails_when_question_is_not_active(
        IsActive $isActive,
        Question $question
    ) {
        $isActive->isSatisfiedBy($question)->willReturn(false);
        $command = new RemoveQuestionCommand($this->questionId);
        $this->shouldThrow(SpecificationFails::class)
            ->during('handle', [$command]);
    }

    function it_fails_when_question_has_answers(
        IsWaitingFirstAnswer $waitingFirstAnswer,
        Question $question
    ) {
        $waitingFirstAnswer->isSatisfiedBy($question)->willReturn(false);
        $command = new RemoveQuestionCommand($this->questionId);
        $this->shouldThrow(SpecificationFails::class)
            ->during('handle', [$command]);
    }

    function it_fails_when_question_is_not_owner_vy_requester(
        OwnedByRequester $byRequester,
        Question $question
    ) {
        $byRequester->isSatisfiedBy($question)->willReturn(false);
        $command = new RemoveQuestionCommand($this->questionId);
        $this->shouldThrow(SpecificationFails::class)
            ->during('handle', [$command]);
    }
}
