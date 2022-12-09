<?php

namespace App\Application\Questions;

use App\Application\Command;
use App\Application\CommandHandler;
use App\Application\CommandHandlerMethods;
use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionRepository;
use App\Domain\UserManagement\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

class PlaceQuestionHandler implements CommandHandler
{
    use CommandHandlerMethods;


    public function __construct(
        private UserRepository $users,
        private QuestionRepository $questions,
        private EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @inheritDoc
     */
    public function handle(Command|PlaceQuestionCommand $command): Question
    {
        $owner = $this->users->withId($command->ownerUserId());
        $question = new Question($owner, $command->title(), $command->body());
        $this->dispatchEventsFrom($this->questions->add($question), $this->dispatcher);
        return $question;
    }
}
