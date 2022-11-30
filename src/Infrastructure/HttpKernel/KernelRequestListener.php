<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\HttpKernel;

use Nyholm\Psr7\Factory\Psr17Factory;
use Slick\JSONAPI\Document\DocumentDecoder;
use Slick\JSONAPI\Document\HttpMessageParser;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * KernelRequestListener
 *
 * @package App\Infrastructure\HttpKernel
 */
#[AsEventListener(event: RequestEvent::class, method: "onKernelRequest", priority: 10)]
final class KernelRequestListener
{

    public function __construct(
        private readonly HttpMessageParser $parser,
        private readonly DocumentDecoder $documentDecoder
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->getRequest()->attributes->get('jsonApi', true)) {
            return;
        }

        $content = $event->getRequest()->getContent();
        if (strlen($content) <= 0) {
            return;
        }

        $factory = new Psr17Factory();
        $psr7Factory = new PsrHttpFactory($factory, $factory, $factory, $factory);
        $document = $this->parser->parse($psr7Factory->createRequest($event->getRequest()));
        $this->documentDecoder->setRequestedDocument($document);
    }
}

