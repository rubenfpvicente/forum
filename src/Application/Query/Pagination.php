<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\Query;

use JsonSerializable;

/**
 * Pagination
 *
 * @package App\Application\Query
 */
final class Pagination implements JsonSerializable
{

    /**
     * Creates a Pagination
     *
     * @param int $rowsPerPage
     * @param int $page
     * @param int $totalRows
     */
    public function __construct(
        private readonly int $rowsPerPage = 12,
        private readonly int $page = 1,
        private int $totalRows = 0
    ) {
    }

    /**
     * rowsPerPage
     *
     * @return int
     */
    public function rowsPerPage(): int
    {
        return $this->rowsPerPage;
    }

    /**
     * page
     *
     * @return int
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * totalRows
     *
     * @return int
     */
    public function totalRows(): int
    {
        return $this->totalRows;
    }

    /**
     * Total pages
     *
     * @return int
     */
    public function totalPages(): int
    {
        return (int) max(1, ceil($this->totalRows / $this->rowsPerPage));
    }

    /**
     * Specify which row to start from retrieving data
     *
     * @return int
     */
    public function offset(): int
    {
        $max = $this->totalRows / $this->rowsPerPage;
        $offset = (int)($this->rowsPerPage * (min($this->page, $max) - 1));
        return $offset <= 0 ? 0 : $offset;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'rowsPerPage' => $this->rowsPerPage,
            'page' => $this->page,
            'totalRows' => $this->totalRows,
            'offset' => $this->offset(),
            'totalPages' => $this->totalPages()
        ];
    }

    /**
     * Returns a new pagination object with the provided total rows value
     *
     * @param int $totalRows
     * @return Pagination
     */
    public function withTotalRows(int $totalRows): Pagination
    {
        $clone = clone $this;
        $clone->totalRows = $totalRows;
        return $clone;
    }
}
