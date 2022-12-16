<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Domain\Questions;

use App\Domain\Exception\EntityNotFound;
use App\Domain\Questions\Question\QuestionId;
use RuntimeException;

/**
 * QuestionRepository
 *
 * @package App\Domain\Questions
 */
interface QuestionRepository
{

    /**
     * Adds a question to the repository
     *
     * @param Question $question
     * @return Question
     */
    public function add(Question $question): Question;

    /**
     * Retrieves a question saved with provided question identifier
     *
     * @param QuestionId $questionId
     * @return Question
     * @throws RuntimeException|EntityNotFound
     */
    public function withQuestionId(QuestionId $questionId): Question;

    /**
     * Removes provided questions from repository
     *
     * @param Question $question
     * @return Question
     */
    public function remove(Question $question): Question;
}
