<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\PaginatedQuery;

/**
 * AbstractPaginatedQuery
 *
 * @package App\Application\Query
 */
abstract class AbstractPaginatedQuery extends AbstractQuery implements PaginatedQuery
{

    private ?Pagination $pagination = null;
    private ?int $totalRows = null;

    /**
     * @inheritDoc
     */
    public function pagination(): Pagination
    {
        if (!$this->pagination) {
            $this->pagination = new Pagination(totalRows: $this->totalRows());
        }
        return $this->pagination;
    }

    public function withPagination(Pagination $pagination): self
    {
        $clone = clone $this;
        $clone->data = null;
        $clone->pagination = $pagination;
        return $clone;
    }

    /**
     * Initializes total rows
     *
     * @return int
     */
    private function totalRows(): int
    {
        if (!$this->totalRows) {
            $this->totalRows = $this->executeCountQuery();
        }

        return $this->totalRows;
    }

    /**
     * Executes the count query needed to set up pagination data.
     *
     * @return int
     */
    abstract protected function executeCountQuery(): int;
}
