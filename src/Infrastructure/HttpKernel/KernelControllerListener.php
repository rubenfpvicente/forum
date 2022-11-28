<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\HttpKernel;

use App\Domain\UserManagement\User\UserId;
use App\Domain\UserManagement\UserIdentifier;
use App\Domain\UserManagement\UserRepository;
use App\UserInterface\AuthenticationAwareController;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\ErrorObject;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Nyholm\Psr7\Response;

/**
 * KernelControllerListener
 *
 * @package App\Infrastructure\HttpKernel
 */
final class KernelControllerListener
{

    /**
     * Creates a KernelControllerListener
     *
     * @param ResourceServer $resourceServer
     * @param UserRepository $users
     * @param UserIdentifier $identifier
     */
    public function __construct(
        private readonly ResourceServer $resourceServer,
        private readonly UserRepository $users,
        private readonly UserIdentifier $identifier
    ) {
    }

    /**
     * onKernelController
     *
     * @param ControllerEvent $event
     * @throws Exception
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if ($event->getController() instanceof ErrorController) {
            return;
        }
        $controller = $event->getController()[0];

        if (! $controller instanceof AuthenticationAwareController) {
            return;
        }

        $factory = new Psr17Factory();
        $psr7Factory = new PsrHttpFactory($factory, $factory, $factory, $factory);

        try {
            $request = $this->resourceServer->validateAuthenticatedRequest($psr7Factory->createRequest($event->getRequest()));
            $this->loadUser($request->getAttribute('oauth_user_id'), $controller);
        } catch (OAuthServerException $e) {
            $event->stopPropagation();
            $failedValidation = new FailedValidation("OAuth 2.0 error");
            throw $failedValidation->addError(new ErrorObject(
                title: $e->getMessage(),
                detail: $e->getHint(),
                status: (string) $e->generateHttpResponse(new Response())->getStatusCode()
            ));
        }
    }

    /**
     * @param string $getUserAttribute
     * @param AuthenticationAwareController $controller
     * @throws Exception
     */
    private function loadUser(string $getUserAttribute, AuthenticationAwareController $controller): void
    {
        $user = $this->users->withId(new UserId($getUserAttribute));
        $controller->withUser($user);
        $this->identifier->withUser($user);
    }
}
