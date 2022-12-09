<?php

namespace App\Domain\Questions\Events;

use App\Domain\AbstractEvent;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\UserManagement\User\UserId;
use JsonSerializable;

class QuestionWasPlaced extends AbstractEvent implements JsonSerializable
{

    /**
     * Creates a QuestionWasPlaced
     *
     * @param UserId $ownerUserId
     * @param QuestionId $questionId
     * @param string $title
     * @param string $body
     * @param bool $closed
     * @param bool $archived
     */
    public function __construct(
        private readonly UserId $ownerUserId,
        private readonly QuestionId $questionId,
        private readonly string $title,
        private readonly string $body,
        private readonly bool $closed,
        private readonly bool $archived
    ) {
        parent::__construct();
    }

    /**
     * ownerUserId
     *
     * @return UserId
     */
    public function ownerUserId(): UserId
    {
        return $this->ownerUserId;
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
     * closed
     *
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * archived
     *
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->archived;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'ownerUserId' => $this->ownerUserId,
            'questionId' => $this->questionId,
            'title' => $this->title,
            'body' => $this->body,
            'closed' => $this->closed,
            'archived' => $this->archived
        ];
    }
}
