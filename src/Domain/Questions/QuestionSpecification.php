<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Domain\Questions;

/**
 * QuestionSpecification
 *
 * @package App\Domain\Questions
 */
interface QuestionSpecification
{

    /**
     * This specification is satisfied by provided question
     *
     * @param Question $question
     * @return bool
     */
    public function isSatisfiedBy(Question $question): bool;
}
