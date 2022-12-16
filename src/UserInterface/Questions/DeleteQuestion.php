<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\UserInterface\Questions;

use App\Application\Questions\RemoveQuestionCommand;
use App\Application\Questions\RemoveQuestionHandler;
use App\Domain\Questions\Question\QuestionId;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DeleteQuestion
 *
 * @package App\UserInterface\Questions
 */
final class DeleteQuestion extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly RemoveQuestionHandler $questionHandler,
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    #[Route(path: "//questions/{questionId}", methods: ['DELETE'])]
    public function handle(string $questionId): Response
    {
        $questionId = new QuestionId($questionId);
        $command = new RemoveQuestionCommand($questionId);
        $this->questionHandler->handle($command);
        $this->entityManager->flush();
        return new Response(status: 204);
    }
}