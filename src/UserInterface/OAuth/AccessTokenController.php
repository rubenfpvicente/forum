<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface\OAuth;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * AccessTokenController
 *
 * @package App\UserInterface\OAuth
 */
final class AccessTokenController extends AbstractController
{

    public function __construct(
        private readonly AuthorizationServer $server,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/auth/access-token', defaults: ['jsonApi' => false])]
    public function login(Request $request): Response
    {
        $factory = new Psr17Factory();
        $psr7Factory = new PsrHttpFactory($factory, $factory, $factory, $factory);
        $httpFoundationFactory = new HttpFoundationFactory();

        $psrResponse = $psr7Factory->createResponse(new Response());
        try {
            $psrRequest = $psr7Factory->createRequest($request);
            $response = $this->server->respondToAccessTokenRequest(
                $psrRequest,
                $psrResponse
            );
        } catch (OAuthServerException $error) {
            return $httpFoundationFactory->createResponse($error->generateHttpResponse($psrResponse));
        } catch (Throwable $error) {
            return new Response(
                json_encode([
                    'error' => 'Internal Server Error',
                    'message' => $error->getMessage()
                ]),
                500,
                ['content-type' => 'application/json']
            );
        }

        $this->entityManager->flush();
        return $httpFoundationFactory->createResponse($response);
    }
}
