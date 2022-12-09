<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface\Questions;

use App\Application\Questions\PlaceQuestionCommand;
use App\Application\Questions\PlaceQuestionHandler;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Doctrine\ORM\EntityManagerInterface;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\DocumentEncoder;
use Slick\JSONAPI\Document\HttpMessageParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * PlaceQuestionController
 *
 * @package App\UserInterface\Questions
 */
final class PlaceQuestionController extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly PlaceQuestionHandler $handler,
        private readonly DocumentDecoder $documentDecoder,
        private readonly DocumentEncoder $documentEncoder,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: "/questions", methods: "POST")]
    public function handle(): Response
    {
        /** @var PlaceQuestionCommand $command */
        $command = $this->documentDecoder->decodeTo(PlaceQuestionCommand::class);
        if (!$this->user()->userId()->equalsTo($command->ownerUserId())) {
            throw new \InvalidArgumentException(
                "The user present in request payload is not the one in the authorization token."
            );
        }
        $question = $this->handler->handle($command);
        $this->entityManager->flush();
        return new Response(
            content: $this->documentEncoder->encode($question),
            status: 201,
            headers: [
                'content-type' => 'application/vnd.api+json',
                'location' => "/questions/{$question->questionId()}"
            ]
        );
    }
}