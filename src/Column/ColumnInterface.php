<?php

declare(strict_types=1);

namespace Percas\Grid\Column;


use Percas\Grid\Filter\FilterInterface;
use Percas\Grid\Header;

interface ColumnInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return Header
     */
    public function getHeader(): Header;

    /**
     * @param mixed $value
     * @return string
     */
    public function getDisplayValue($value): string;

    /**
     * @param bool $sortable
     * @return $this
     */
    public function setSortable(bool $sortable);

    /**
     * @param bool $filterable
     * @return $this
     */
    public function setFilterable(bool $filterable);

    /**
     * @param FilterInterface[] $filters
     * @return $this
     */
    public function setFilters(array $filters);

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter);

    /**
     * @return FilterInterface[]
     */
    public function getDefaultFilters(): array;
}
