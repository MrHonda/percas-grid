<?php

declare(strict_types=1);


namespace Percas\Grid;


class GridState
{
    public const SORT_ASC = 'ASC';
    public const SORT_DESC = 'DESC';

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
}
