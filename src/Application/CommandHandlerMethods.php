<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application;

use App\Domain\RootAggregate;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * CommandHandlerMethods trait
 *
 * @package App\Application
 */
trait CommandHandlerMethods
{

    /**
     * Dispatch events from a root aggregate
     *
     * @param RootAggregate $aggregate
     * @param EventDispatcherInterface $dispatcher
     */
    public function dispatchEventsFrom(RootAggregate $aggregate, EventDispatcherInterface $dispatcher): void
    {
        foreach ($aggregate->releaseEvents() as $event) {
            $dispatcher->dispatch($event);
        }
    }
}