<?php

namespace App\Application\Questions;

use App\Application\Command;
use App\Domain\Questions\Question\QuestionId;

class RemoveQuestionCommand implements Command
{

    public function __construct(private readonly QuestionId $questionId)
    {
    }

    /**
     * questionId
     *
     * @return QuestionId
     */
    public function questionId(): QuestionId
    {
        return $this->questionId;
    }
}
