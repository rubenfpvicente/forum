<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\UserInterface\Questions;

use App\Application\Questions\ChangeQuestionCommand;
use App\Application\Questions\ChangeQuestionHandler;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Doctrine\ORM\EntityManagerInterface;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ChangeQuestion
 *
 * @package App\UserInterface\Questions
 */
final class ChangeQuestion  extends AbstractController implements AuthenticationAwareController
{
     use AuthenticationAwareMethods;

     public function __construct(
         private readonly DocumentDecoder $documentDecoder,
         private readonly ChangeQuestionHandler $questionHandler,
         private readonly DocumentEncoder $documentEncoder,
         private readonly EntityManagerInterface $entityManager
     ) {
     }

    #[Route(path: "/questions/{questionId}", methods: ["PATCH", "POST"])]
     public function handle(string $questionId): Response
     {
        $command = $this->documentDecoder->decodeTo(ChangeQuestionCommand::class);
        $question = $this->questionHandler->handle($command);
        $this->entityManager->flush();

         return new Response(
             content: $this->documentEncoder->encode($question),
             headers: [
                 'content-type' => 'application/vnd.api+json'
             ]
         );
     }
}