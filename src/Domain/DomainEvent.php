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

/**
 * DomainEvent
 *
 * @package App\Domain
 */
interface DomainEvent
{

    /**
     * Date and time event has occurred
     *
     * @return DateTimeImmutable
     */
    public function occurredOn(): DateTimeImmutable;
}
