<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


use Percas\Grid\Column\ColumnInterface;

abstract class AbstractDatabaseDataSource implements DataSourceInterface
{
    /**
     * @param string $primaryKey
     * @param ColumnInterface[] $columns
     * @return string
     */
    protected function prepareCols(string $primaryKey, array $columns): string
    {
        $cols = [];
        $cols[] = $primaryKey;

        foreach ($columns as $column) {
            $key = $column->getKey();

            if ($key !== '' && !in_array($key, $cols, true)) {
                $cols[] = $key;
            }
        }

        return implode(',', $cols);
    }
}
