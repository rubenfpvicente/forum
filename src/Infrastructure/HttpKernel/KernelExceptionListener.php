<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\HttpKernel;

use App\Domain\Exception\EntityNotFound;
use Slick\JSONAPI\Document\DocumentEncoder;
use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\ErrorObject;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * KernelExceptionListener
 *
 * @package App\Infrastructure\HttpKernel
 */
#[AsEventListener(event: ExceptionEvent::class, method: "onKernelException", priority: 10)]
final class KernelExceptionListener
{

    public function __construct(private readonly DocumentEncoder $documentEncoder)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof FailedValidation) {
            $event->setResponse($this->createResponse($exception));
            return;
        }

        if (
            $exception instanceof EntityNotFound ||
            $exception instanceof NotFoundHttpException
        ) {
            $failedValidation = new FailedValidation($exception->getMessage(), 0, $exception);
            $failedValidation->addError(new ErrorObject(
                'Not found',
                $exception->getMessage(),
                null,
                "404"
            ));
            $event->setResponse($this->createResponse($failedValidation));
            return;
        }

        if ($exception instanceof \InvalidArgumentException) {
            $failedValidation = new FailedValidation($exception->getMessage(), 0, $exception);
            $failedValidation->addError(new ErrorObject(
                'Bad request',
                $exception->getMessage(),
                null,
                "400"
            ));
            $event->setResponse($this->createResponse($failedValidation));
            return;
        }

        $failedValidation = new FailedValidation($exception->getMessage(), 0, $exception);
        $failedValidation->addError(new ErrorObject(
            get_class($exception),
            $exception->getMessage(),
            null,
            "500"
        ));
        $event->setResponse($this->createResponse($failedValidation));
    }

    private function createResponse(FailedValidation $failedValidation): Response
    {
        $firstError = $failedValidation->document()->errors()->first();
        return new Response(
            $this->documentEncoder->encode($failedValidation->document()),
            (int) $firstError->status(),
            ['content-type' => "application/vnd.api+json"]
        );
    }
}
