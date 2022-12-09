<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface\Questions;

use App\Application\Questions\QuestionsListQuery;
use App\Infrastructure\JsonApi\SymfonyParameterReader;
use App\UserInterface\AuthenticationAwareController;
use App\UserInterface\AuthenticationAwareMethods;
use Slick\JSONAPI\Document\DocumentEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * QuestionsListController
 *
 * @package App\UserInterface\Questions
 */
final class QuestionsListController extends AbstractController implements AuthenticationAwareController
{

    use AuthenticationAwareMethods;

    public function __construct(
        private readonly QuestionsListQuery $listQuery,
        private readonly DocumentEncoder $encoder
    ) {
    }

    #[Route(path: "/questions", methods: ["GET"])]
    public function handle(Request $request): Response
    {
        $parameterReader = new SymfonyParameterReader($request);
        $owner = QuestionsListQuery::OWNER_ALL;
        $queryFilter = $request->query->all('filter');

        if (array_key_exists(QuestionsListQuery::OWNER_FILTER, $queryFilter)) {
            $owner = $queryFilter[QuestionsListQuery::OWNER_FILTER];
        }

        $questionsList = $this->listQuery
            ->withParameterReader($parameterReader)
            ->withParam(QuestionsListQuery::PARAM_USER_ID, $this->user()->userId())
            ->withParam(QuestionsListQuery::OWNER_FILTER, $owner);

        return new Response(
            content: $this->encoder->encode($questionsList),
            headers: ['content-type' => 'application/vnd.api+json']
        );
    }
}