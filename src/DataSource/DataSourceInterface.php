<?php

declare(strict_types=1);

namespace Percas\Grid\DataSource;


use Percas\Grid\DataFilter;
use Percas\Grid\GridState;

interface DataSourceInterface
{
    /**
     * @param string[] $columns
     * @param DataFilter[] $filters
     * @param GridState $state
     * @return array
     */
    public function getData(array $columns, array $filters, GridState $state): array;

    /**
     * @param array $filters
     * @param GridState $state
     * @return int
     */
    public function getDataCount(array $filters, GridState $state): int;
}
