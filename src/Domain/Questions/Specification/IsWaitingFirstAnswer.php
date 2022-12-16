<?php

namespace App\Domain\Questions\Specification;

use App\Domain\Questions\Question;
use App\Domain\Questions\QuestionSpecification;

class IsWaitingFirstAnswer implements QuestionSpecification
{

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(Question $question): bool
    {
        return $question->answers()->isEmpty();
    }
}
