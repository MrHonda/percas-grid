<?php

declare(strict_types=1);


namespace Percas\Grid;


use Percas\Grid\Filter\FilterInterface;

class Header
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $sortable = true;

    /**
     * @var bool
     */
    private $filterable = true;

    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * Header constructor.
     * @param string $key
     * @param string $name
     */
    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     * @return Header
     */
    public function setSortable(bool $sortable): Header
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * @return bool
     */
    public function hasFilters(): bool
    {
        return count($this->filters) > 0;
    }

    /**
     * @param bool $filterable
     * @return Header
     */
    public function setFilterable(bool $filterable): Header
    {
        $this->filterable = $filterable;
        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param FilterInterface[] $filters
     * @return Header
     */
    public function setFilters(array $filters): Header
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @param FilterInterface $filter
     * @return Header
     */
    public function addFilter(FilterInterface $filter): Header
    {
        $this->filters[] = $filter;
        return $this;
    }
}
