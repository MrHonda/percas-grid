<?php

declare(strict_types=1);


namespace Percas\Grid;


class GridState
{
    public const SORT_ASC = 'ASC';
    public const SORT_DESC = 'DESC';

    public const DEFAULT_RECORDS_PER_PAGE = 10;

    /**
     * @var string[]
     */
    private $filters = [];

    /**
     * @var string
     */
    private $sorted_by = '';

    /**
     * @var string
     */
    private $sort_direction = '';

    /**
     * @var int
     */
    private $current_page = 1;

    /**
     * @var int
     */
    private $records_per_page = self::DEFAULT_RECORDS_PER_PAGE;

    /**
     * @param int $index
     * @return string
     */
    public function getFilter(int $index): string
    {
        return $this->filters[$index] ?? '';
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param string[] $filters [index => value]
     * @return GridState
     */
    public function setFilters(array $filters): GridState
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @param int $index
     * @param string $value
     * @return GridState
     */
    public function setFilter(int $index, string $value): GridState
    {
        $this->filters[$index] = $value;
        return $this;
    }

    public function isSorted(): bool
    {
        return $this->sorted_by !== '' && $this->sort_direction !== '';
    }

    /**
     * @return string
     */
    public function getSortedBy(): string
    {
        return $this->sorted_by;
    }

    /**
     * @param string $sorted_by
     * @return GridState
     */
    public function setSortedBy(string $sorted_by): GridState
    {
        $this->sorted_by = $sorted_by;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortDirection(): string
    {
        return strtoupper($this->sort_direction) !== self::SORT_DESC ? self::SORT_ASC : self::SORT_DESC;
    }

    /**
     * @param string $sort_direction - GridState::SORT_ASC | GridState::SORT_DESC
     * @return GridState
     */
    public function setSortDirection(string $sort_direction): GridState
    {
        $this->sort_direction = $sort_direction;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    /**
     * @param int $current_page
     * @return GridState
     */
    public function setCurrentPage(int $current_page): GridState
    {
        $this->current_page = $current_page;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsPerPage(): int
    {
        return $this->records_per_page;
    }

    /**
     * @param int $records_per_page
     * @return GridState
     */
    public function setRecordsPerPage(int $records_per_page): GridState
    {
        $this->records_per_page = $records_per_page;
        return $this;
    }
}
