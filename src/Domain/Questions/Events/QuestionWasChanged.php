<?php

namespace App\Domain\Questions\Events;

use App\Domain\AbstractEvent;
use App\Domain\Questions\Question\QuestionId;

class QuestionWasChanged extends AbstractEvent implements \JsonSerializable
{
    /**
     * Creates a QuestionWasChanged
     *
     * @param QuestionId $questionId
     * @param string $title
     * @param string $body
     */
    public function __construct(
        private readonly QuestionId $questionId,
        private readonly string $title,
        private readonly string $body
    ) {
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
     * title
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * body
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'questionId' => $this->questionId,
            'title' => $this->title,
            'body' => $this->body
        ];
    }
}
