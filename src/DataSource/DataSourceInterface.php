<?php

declare(strict_types=1);

namespace Percas\Grid\DataSource;


use Percas\Grid\GridState;

interface DataSourceInterface
{
    /**
     * @param string[] $columns
     * @param GridState $state
     * @return array
     */
    public function getData(array $columns, GridState $state): array;
}
