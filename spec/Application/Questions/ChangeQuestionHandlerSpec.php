<?php

namespace spec\App\Application\Questions;

use App\Application\CommandHandler;
use App\Application\Questions\ChangeQuestionCommand;
use App\Application\Questions\ChangeQuestionHandler;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Questions\Events\QuestionWasChanged;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\Questions\Specification\IsWaitingFirstAnswer;
use App\Domain\Questions\Specification\OwnedByRequester;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ChangeQuestionHandlerSpec extends ObjectBehavior
{

    private $questionId;
    private $title;
    private $body;

    function let(
        QuestionRepository $questions,
        OwnedByRequester $ownedByRequester,
        IsWaitingFirstAnswer $waitingFirstAnswer,
        EventDispatcher $dispatcher,
        Question $question,
        QuestionWasChanged $event
    ) {
        $this->questionId = new Question\QuestionId();
        $this->title = 'some title';
        $this->body = 'some body';

        $questions->withQuestionId($this->questionId)->willReturn($question);

        $question->questionId()->willReturn($this->questionId);
        $question->change($this->title, $this->body)->willReturn($question);
        $question->releaseEvents()->willReturn([$event]);

        $dispatcher->dispatch(Argument::any())->willReturnArgument();

        $waitingFirstAnswer->isSatisfiedBy($question)->willReturn(true);
        $ownedByRequester->isSatisfiedBy($question)->willReturn(true);

        $this->beConstructedWith($questions, $ownedByRequester, $waitingFirstAnswer, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ChangeQuestionHandler::class);
    }

    function its_a_command_handler()
    {
        $this->shouldBeAnInstanceOf(CommandHandler::class);
    }

    function it_handles_change_question_command(
        Question $question
    ) {
        $command = new ChangeQuestionCommand(
            $this->questionId,
            $this->title,
            $this->body
        );

        $this->handle($command)->shouldBe($question);
        $question->change($this->title, $this->body)->shouldBeCalled();
    }

    function it_triggers_change_events(
        EventDispatcher $dispatcher,
        QuestionWasChanged $event
    ) {
        $command = new ChangeQuestionCommand(
            $this->questionId,
            $this->title,
            $this->body
        );

        $this->handle($command);
        $dispatcher->dispatch($event)->shouldHaveBeenCalled();
    }

    function it_throws_exception_when_questions_has_at_least_one_answer(
        IsWaitingFirstAnswer $waitingFirstAnswer,
        Question $question
    ) {

        $waitingFirstAnswer->isSatisfiedBy($question)->willReturn(false);

        $command = new ChangeQuestionCommand(
            $this->questionId,
            $this->title,
            $this->body
        );
        $this->shouldThrow(SpecificationFails::class)
            ->during('handle', [$command]);
    }

    function it_throws_exception_when_changing_user_is_not_the_owner(
        OwnedByRequester $ownedByRequester,
        Question $question
    ) {
        $ownedByRequester->isSatisfiedBy($question)->willReturn(false);

        $command = new ChangeQuestionCommand(
            $this->questionId,
            $this->title,
            $this->body
        );
        $this->shouldThrow(SpecificationFails::class)
            ->during('handle', [$command]);
    }
}
