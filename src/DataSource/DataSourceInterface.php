<?php

declare(strict_types=1);

namespace Percas\Grid\DataSource;


use Percas\Grid\Column\ColumnInterface;
use Percas\Grid\GridState;

interface DataSourceInterface
{
    /**
     * @param string $primaryKey
     * @param ColumnInterface[] $columns
     * @param GridState $state
     * @return array
     */
    public function getData(string $primaryKey, array $columns, GridState $state): array;
}
