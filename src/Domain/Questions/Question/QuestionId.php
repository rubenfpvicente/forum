<?php

namespace App\Domain\Questions\Question;

use App\Domain\Exception\InvalidAggregateIdentifier;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class QuestionId implements \Stringable, JsonSerializable
{
    public function __construct(private ?string $questionIdStr = null)
    {
        $this->questionIdStr = $this->questionIdStr ?: Uuid::uuid4()->toString();

        if (!Uuid::isValid($this->questionIdStr)) {
            throw new InvalidAggregateIdentifier(
                "The provided question identifier is not a valid UUID."
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->questionIdStr;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->questionIdStr;
    }
}
