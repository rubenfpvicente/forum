<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\HttpKernel;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * KernelResponseListener
 *
 * @package App\Infrastructure\HttpKernel
 */
#[AsEventListener(event: ResponseEvent::class, method: "onKernelResponse", priority: 10)]
final class KernelResponseListener
{

    public function onKernelResponse(ResponseEvent $event): void
    {
        $event->getResponse()->headers->add([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => 'origin, x-requested-with, content-type, authorization',
            'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, HEAD, DELETE, OPTIONS'
        ]);
    }

}