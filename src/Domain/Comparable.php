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
 * Comparable
 *
 * @package App\Domain
 */
interface Comparable
{

    /**
     * Compares provided object for equality
     *
     * @param mixed $other
     * @return bool
     */
    public function equalsTo(mixed $other): bool;
}
