<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\Application;

use App\Application\Query\Pagination;

/**
 * PaginatedQuery
 *
 * @package App\Application
 */
interface PaginatedQuery extends Query
{

    /**
     * Query pagination data pagination
     *
     * @return Pagination
     */
    public function pagination(): Pagination;

    /**
     * Creates a new query with provided pagination
     *
     * NOTE: Every call to this method MUST return a new instance, with empty data set.
     *
     * @param Pagination $pagination
     * @return PaginatedQuery|self
     */
    public function withPagination(Pagination $pagination): self;
}
