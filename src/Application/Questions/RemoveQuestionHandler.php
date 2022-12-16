<?php

namespace App\Application\Questions;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
use App\Domain\Exception\SpecificationFails;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\Questions\Specification\IsActive;
use App\Domain\Questions\Specification\IsWaitingFirstAnswer;
use App\Domain\Questions\Specification\OwnedByRequester;
use Psr\EventDispatcher\EventDispatcherInterface;

class RemoveQuestionHandler implements CommandHandler
{
    use CommandHandlerMethods;

    public function __construct(
        private readonly QuestionRepository $questions,
        private readonly IsActive $isActive,
        private readonly IsWaitingFirstAnswer $waitingFirstAnswer,
        private readonly OwnedByRequester $byRequester,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     * @param RemoveQuestionCommand $command
     */
    public function handle(Command $command): Question
    {
        $question = $this->questions->withQuestionId($command->questionId());


        if (!$this->waitingFirstAnswer->isSatisfiedBy($question)) {
            throw new SpecificationFails(
                "Could not remove selected question. " .
                "Questions can only be removed when they haven't answers yet."
            );
        }

        if (!$this->byRequester->isSatisfiedBy($question)) {
            throw new SpecificationFails(
                "Could not remove selected question. " .
                "Questions can only be removed by its owner."
            );
        }

        if (!$this->isActive->isSatisfiedBy($question)) {
            throw new SpecificationFails(
                "Could not remove selected question. " .
                "Question is closed or archive."
            );
        }

        $this->dispatchEventsFrom(
            $this->questions->remove($question),
            $this->dispatcher
        );
        return $question;
    }
}
