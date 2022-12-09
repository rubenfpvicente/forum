<?php

namespace App\Application\Questions;

use App\Application\Command;
use App\Domain\UserManagement\User\UserId;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\RelationshipIdentifier;

/**
 * PlaceQuestionCommand
 *
 * @package App\Application\Questions
 */
#[AsResourceObject(type: "questions")]
class PlaceQuestionCommand implements Command
{
    /**
     * Creates a PlaceQuestionCommand
     *
     * @param UserId $ownerUserId
     * @param string $title
     * @param string $body
     */
    public function __construct(
        #[RelationshipIdentifier(name: "owner", className: UserId::class, type: 'users')]
        private readonly UserId $ownerUserId,

        #[Attribute(required: true)]
        private readonly string $title,

        #[Attribute(required: true)]
        private readonly string $body
    ) {
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
}
