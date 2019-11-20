<?php

declare(strict_types=1);


namespace Percas\Grid;


class GridState
{
    /**
     * @var string
     */
    private $sorted_by = '';

    /**
     * @var string
     */
    private $sort_direction = '';

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
        return $this->sort_direction;
    }

    /**
     * @param string $sort_direction
     * @return GridState
     */
    public function setSortDirection(string $sort_direction): GridState
    {
        $this->sort_direction = $sort_direction;
        return $this;
    }
}
