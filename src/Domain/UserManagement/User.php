<?php

namespace App\Domain\UserManagement;

use App\Domain\RootAggregate;
use App\Domain\RootAggregateMethods;
use App\Domain\UserManagement\Events\UserWasRegistered;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\Password;
use App\Domain\UserManagement\User\UserId;
use App\Infrastructure\JsonApi\SchemaDiscovery\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\ResourceIdentifier;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use League\OAuth2\Server\Entities\UserEntityInterface;
use OpenApi\Attributes as OA;

/**
 * User
 *
 * @package App\Domain\UserManagement
 */
#[
    OA\Schema(
        schema: "UserDocument",
        title: "User Document",
        description: "An application user",
        properties: [
            new OA\Property(property: "jsonapi", ref: "#/components/schemas/jsonApiVersion"),
            new OA\Property(property: "data", properties: [
                new OA\Property(property: "type", type: "string", example: "users"),
                new OA\Property(property: "id", type: "string", example: "a697edde-a551-407b-9a1b-9e8f107fbd41"),
                new OA\Property(property: "attributes", properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", example: "john.doe@example.com"),
                ], type: "object"),
                new OA\Property(property: "links", properties: [
                    new OA\Property(property: "self", type: "string", example: "/users/a697edde-a551-407b-9a1b-9e8f107fbd41"),
                ], type: "object"),
            ], type: "object"),
        ]
    )
]
#[
    Entity,
    Table(name: "users"),
    Index(fields: ["email"], name: "emailIdx")
]
#[AsResourceObject(type: "users", links: [AsResourceObject::LINK_SELF])]
class User implements RootAggregate, UserEntityInterface
{

    use RootAggregateMethods;

    #[Id]
    #[GeneratedValue(strategy: 'NONE')]
    #[Column(name: 'id', type: 'UserId')]
    #[ResourceIdentifier]
    private UserId $userId;

    /**
     * Creates a User
     *
     * @param string $name
     * @param Email $email
     * @param Password|null $password
     */
    public function __construct(
        #[Column, Attribute]
        private string $name,
        #[Column(type: 'Email'), Attribute]
        private Email $email,
        #[Column(type: 'Password')]
        private ?Password $password = null
    ) {
        $this->userId = new UserId();
        $this->password = $this->password ?: new Password();
        $this->recordThat(new UserWasRegistered(
            $this->userId,
            $this->name,
            $this->email,
            (string) $this->password
        ));
    }

    /**
     * userId
     *
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * email
     *
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }

    /**
     * password
     *
     * @return Password
     */
    public function password(): Password
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return (string) $this->userId;
    }
}
