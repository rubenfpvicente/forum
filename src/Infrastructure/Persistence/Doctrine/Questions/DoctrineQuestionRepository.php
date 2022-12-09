<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Questions;

use App\Domain\Exception\EntityNotFound;
use App\Domain\Questions\Question;
use App\Domain\Questions\Question\QuestionId;
use App\Domain\Questions\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * DoctrineQuestionRepository
 *
 * @package App\Infrastructure\Persistence\Doctrine\Questions
 */
final class DoctrineQuestionRepository implements QuestionRepository
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    /**
     * @inheritDoc
     */
    public function add(Question $question): Question
    {
        $this->entityManager->persist($question);
        return $question;
    }

    /**
     * @inheritDoc
     */
    public function withQuestionId(QuestionId $questionId): Question
    {
        $question = $this->entityManager->find(Question::class, $questionId);

        if ($question instanceof Question) {
            return $question;
        }

        throw new EntityNotFound("There are no questions with ID '$questionId'");
    }
}
