<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface\Questions;

use App\Domain\Questions\Question\QuestionId;
use App\Domain\Questions\QuestionRepository;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ReadQuestionController
 *
 * @package App\UserInterface\Questions
 */
final class ReadQuestionController extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly  QuestionRepository $questions,
        private readonly DocumentEncoder $documentEncoder
    ) {
    }

    #[Route(path: "/questions/{id}")]
    public function read(string $id): Response
    {
        $questionId = new QuestionId($id);
        $question = $this->questions->withQuestionId($questionId);
        return new Response(
            content: $this->documentEncoder->encode($question),
            headers: ['content-type' => 'application/vnd.api+json']
        );
    }
}
