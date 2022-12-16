<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi\Questions;

use App\Application\Questions\ChangeQuestionCommand;
use App\Domain\Questions\Question\QuestionId;
use Ramsey\Uuid\Uuid;
use Slick\JSONAPI\Object\AbstractResourceSchema;
use Slick\JSONAPI\Object\ResourceSchema;

/**
 * CreateQuestionCommandSchema
 *
 * @package App\Infrastructure\JsonApi\Questions
 */
final class ChangeQuestionCommandSchema extends AbstractResourceSchema implements ResourceSchema
{

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        return 'questions';
    }

    /**
     * @inheritDoc
     */
    public function identifier($object): ?string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @inheritDoc
     */
    public function from($resourceObject): ChangeQuestionCommand
    {
        $questionId = new QuestionId($resourceObject->resourceIdentifier()->identifier());
        $attributes = (object) $resourceObject->attributes();
        return new ChangeQuestionCommand(
            $questionId,
            $attributes->title ?: null,
            $attributes->body ?: null
        );
    }


}