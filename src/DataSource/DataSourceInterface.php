<?php

declare(strict_types=1);

namespace Percas\Grid\DataSource;


use Percas\Grid\Column\ColumnInterface;

interface DataSourceInterface
{
    /**
     * @param string $primaryKey
     * @param ColumnInterface[] $columns
     * @return array
     */
    public function getData(string $primaryKey, array $columns): array;
}
