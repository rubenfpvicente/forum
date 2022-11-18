<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Domain;

/**
 * RootAggregateMethods trait
 *
 * @package App\Domain
 */
trait RootAggregateMethods
{

    protected array $events = [];

    /**
     * @inheritDoc
     */
    public function recordThat(DomainEvent $event): self
    {
        $this->events[] = $event;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
