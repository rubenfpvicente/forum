<?php

namespace App\Domain\Questions;

use App\Domain\Questions\Events\QuestionWasChanged;
use App\Domain\Questions\Events\QuestionWasPlaced;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\RootAggregate;
use App\Domain\RootAggregateMethods;
use App\Domain\UserManagement\User;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Relationship;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;

#[
    Entity,
    Table(name: "questions")
]
#[AsResourceObject(type: 'questions', links: [AsResourceObject::LINK_SELF], isCompound: true)]
class Question implements JsonSerializable, RootAggregate
{

    use RootAggregateMethods;

    #[Id, GeneratedValue(strategy: 'NONE'), Column(name: 'id', type: 'QuestionId')]
    #[ResourceIdentifier]
    private QuestionId $questionId;

    #[Column(type: "boolean", options: ["default" => 0])]
    #[Attribute(name: "isClosed")]
    private bool $closed = false;

    #[Column(type: "boolean", options: ["default" => 0])]
    #[Attribute(name: "isArchived")]
    private bool $archived = false;

    private ?Collection $answers = null;

    public function __construct(
        #[ManyToOne(targetEntity: User::class, fetch: "EAGER")]
        #[JoinColumn(name: "owner_id", onDelete: "CASCADE")]
        #[Relationship(
            type: Relationship::TO_ONE,
            links: [AsResourceObject::LINK_RELATED],
            meta: ['description' => "A question is owned by a user with is it owner."])
        ]
        private User $owner,
        #[Column]
        #[Attribute]
        private string $title,
        #[Column]
        #[Attribute]
        private string $body
    ) {
        $this->questionId = new QuestionId();
        $this->answers = new ArrayCollection();

        $this->recordThat(new QuestionWasPlaced(
            $this->owner->userId(),
            $this->questionId,
            $this->title,
            $this->body,
            $this->closed,
            $this->archived
        ));
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
     * owner
     *
     * @return User
     */
    public function owner(): User
    {
        return $this->owner;
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
     * answers
     *
     * @return Collection
     */
    public function answers(): Collection
    {
        if (!$this->answers) {
            $this->answers = new ArrayCollection();
        }
        return $this->answers;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'questionId' => $this->questionId,
            'title' => $this->title,
            'body' => $this->body,
            'owner' => $this->owner,
            'archived' => $this->archived,
            'closed' => $this->closed
        ];
    }

    public function change(?string $title = null, ?string $body = null): self
    {
        $this->title = $title ?: $this->title;
        $this->body = $body ?: $this->body;
        $this->recordThat(new QuestionWasChanged($this->questionId, $this->title, $this->body));
        return $this;
    }
}
