<?php

namespace App\Domain\Questions\Events;

use App\Domain\AbstractEvent;
use App\Domain\DomainEvent;
use App\Domain\Questions\Question\QuestionId;
use JsonSerializable;

class QuestionWasRemoved extends AbstractEvent implements DomainEvent, JsonSerializable
{
    /**
     * Creates a QuestionWasRemoved
     *
     * @param QuestionId $questionId
     */
    public function __construct(private readonly QuestionId $questionId)
    {
        parent::__construct();
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


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return  [
            'questionId' => $this->questionId
        ];
    }
}
