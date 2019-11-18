<?php

declare(strict_types=1);

namespace Percas\Grid\DataSource;


interface DataSourceInterface
{
    /**
     * @return array
     */
    public function getData(): array;
}
