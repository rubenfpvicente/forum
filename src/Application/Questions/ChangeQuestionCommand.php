<?php

namespace App\Application\Questions;

use App\Application\Command;
use App\Domain\Questions\Question\QuestionId;
use App\Infrastructure\JsonApi\Questions\ChangeQuestionCommandSchema;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;

#[AsResourceObject(schemaClass: ChangeQuestionCommandSchema::class)]
class ChangeQuestionCommand implements Command
{
    public function __construct(
        private readonly QuestionId $questionId,
        private readonly ?string $title = null,
        private readonly ?string $body = null
    ) {
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
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * body
     *
     * @return string|null
     */
    public function body(): ?string
    {
        return $this->body;
    }
}
