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

    private ?int $rowsPerPage = null;

    private ?int $page = null;

    /**
     * @inheritDoc
     */
    public function pagination(): Pagination
    {
        if (!$this->pagination) {
            $this->pagination = new Pagination(
                rowsPerPage: $this->rowsPerPage(),
                page: $this->page(),
                totalRows: $this->totalRows()
            );
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

    /**
     * Base URL for pagination generated links
     *
     * @return string
     */
    abstract protected function baseUrlPath(): string;

    /**
     * JSON API meta data encoder
     *
     * @return array
     */
    public function metaData(): array
    {
        return [
            'totalPages' => $this->pagination()->totalPages(),
            'currentPage' => min($this->pagination()->page(), $this->pagination()->totalPages()),
            'totalRows' => $this->pagination()->totalRows(),
            'rowsPerPage' => $this->pagination()->rowsPerPage()
        ];
    }

    /**
     * paginationLinks
     *
     * @return array<string, string>
     */
    public function paginationLinks(): array
    {
        static $links = null;
        if ($links) {
            return $links;
        }

        $url = $this->baseUrlPath();
        $limit = $this->rowsPerPage();
        $total = $this->totalRows();

        $offset = function (int $page) use ($total, $limit) {
            $max = $total / $limit;
            $offset = (int)($limit * (min($page, $max) - 1));
            return $offset <= 0 ? 0 : $offset;
        };

        $offsetFirst = $offset(1);
        $offsetPrev = $offset($this->pagination()->page() - 1);
        $offsetNext = $offset($this->pagination()->page() + 1);
        $offsetLast = $offset($this->pagination()->totalPages());

        $links = [];
        if ($this->pagination()->page() > 1) {
            $links = [
                'first' => "$url?page[limit]=$limit&page[offset]={$offsetFirst}",
                'prev'  => "$url?page[limit]=$limit&page[offset]={$offsetPrev}"
            ];
        }

        if ($this->pagination()->page() < $this->pagination()->totalPages()) {
            $links = array_merge($links, [
                'next'  => "$url?page[limit]=$limit&page[offset]={$offsetNext}",
                'last'  => "$url?page[limit]=$limit&page[offset]={$offsetLast}"
            ]);
        }

        return $links;
    }

    /**
     * Total rows per page from default or parameter
     *
     * @return int
     */
    private function rowsPerPage(): int
    {
        $param = $this->param('page', []);
        $page = array_merge(['limit' => 12], $param);
        if (!$this->rowsPerPage) {
            $this->rowsPerPage = (int) $page['limit'];
        }

        return $this->rowsPerPage;
    }

    private function page(): int
    {
        $param = $this->param('page', []);
        $page = array_merge(['offset' => 1], $param);
        if (!$this->page) {
            $this->page = (int) (($page['offset'] / $this->rowsPerPage()) + 1);
        }

        return $this->page;
    }
}
