<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\Query;

/**
 * ParameterReader
 *
 * @package App\Application\Query
 */
interface ParameterReader
{

    /**
     * Pagination parameters
     *
     * Possible key values are 'offset' and 'limit'
     *
     * @return array<string, int>|null
     */
    public function pagination(): ?array;
}