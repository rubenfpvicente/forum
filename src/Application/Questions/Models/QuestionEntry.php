<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\Questions\Models;

use App\Domain\Questions\Question\QuestionId;
use App\Domain\UserManagement\User\Email;
use App\Domain\UserManagement\User\UserId;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceObject;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\Attribute;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\ResourceIdentifier;

/**
 * QuestionEntry
 *
 * @package App\Application\Questions\Models
 */
#[AsResourceObject(type: "questions")]
final class QuestionEntry
{

    #[ResourceIdentifier]
    private readonly QuestionId $questionId;
    #[Attribute]
    private readonly string $title;
    #[Attribute]
    private readonly string $body;
    #[Attribute]
    private readonly UserId $userId;
    #[Attribute]
    private readonly string $name;
    #[Attribute]
    private readonly Email $email;

    public function __construct(array $data)
    {
        $this->questionId = new QuestionId($data['questionId']);
        $this->userId = new UserId($data['userId']);
        $this->email = new Email($data['email']);
        $this->title = $data['title'];
        $this->name = $data['name'];
        $this->body = $data['body'];
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
}
