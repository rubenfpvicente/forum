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
 * Aggregate
 *
 * @package App\Domain
 */
interface RootAggregate
{

    /**
     * Records a new event
     *
     * @param DomainEvent $event
     * @return self
     */
    public function recordThat(DomainEvent $event): self;

    /**
     * Releases (and clears) all events of the aggregate
     *
     * @return array|DomainEvent[]
     */
    public function releaseEvents(): array;
}
