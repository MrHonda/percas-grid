<?php

declare(strict_types=1);


namespace Percas\Grid;


class Row
{
    /**
     * @var DisplayColumn[]
     */
    private $columns = [];

    /**
     * Row constructor.
     * @param DisplayColumn[] $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return DisplayColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
