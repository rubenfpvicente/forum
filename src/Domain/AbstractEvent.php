<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * AbstractEvent
 *
 * @package App\Domain
 */
abstract class AbstractEvent extends Event implements DomainEvent
{

    protected DateTimeImmutable $occurredOn;

    /**
     * Creates a AbstractEvent
     *
     */
    public function __construct()
    {
        $this->occurredOn = new DateTimeImmutable();
    }

    /**
     * Date and time event has occurred
     *
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
