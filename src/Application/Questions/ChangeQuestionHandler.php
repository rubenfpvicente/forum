<?php

namespace App\Application\Questions;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\Questions\Specification\IsWaitingFirstAnswer;
use App\Domain\Questions\Specification\OwnedByRequester;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ChangeQuestionHandler implements CommandHandler
{
    use CommandHandlerMethods;

    public function __construct(
        private readonly QuestionRepository $questions,
        private readonly OwnedByRequester $ownedByRequester,
        private readonly IsWaitingFirstAnswer $waitingFirstAnswer,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     * @param ChangeQuestionCommand $command
     */
    public function handle(Command $command): Question
    {
        $question = $this->questions->withQuestionId($command->questionId());

        if (!$this->waitingFirstAnswer->isSatisfiedBy($question)) {
            throw new SpecificationFails(
                "Could not change selected question. " .
                "Questions can only be changed when they haven't answers yet."
            );
        }

        if (!$this->ownedByRequester->isSatisfiedBy($question)) {
            throw new SpecificationFails(
                "Could not change selected question. " .
                "Questions can only be changed by its owner."
            );
        }

        $this->dispatchEventsFrom(
            $question->change($command->title(), $command->body()),
            $this->dispatcher
        );
        return $question;
    }
}
