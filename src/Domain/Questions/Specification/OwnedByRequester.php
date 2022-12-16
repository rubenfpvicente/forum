<?php

namespace App\Domain\Questions\Specification;

use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionSpecification;
use App\Domain\UserManagement\UserIdentifier;

class OwnedByRequester implements QuestionSpecification
{
    public function __construct(
        private readonly UserIdentifier $identifier
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(Question $question): bool
    {
        $loggedInUserId = $this->identifier->currentUser()->userId();
        return $loggedInUserId->equalsTo($question->owner()->userId());
    }
}
