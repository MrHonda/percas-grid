<?php

declare(strict_types=1);

namespace Percas\Grid;


class Grid
{
    /**
     * @var Header[]
     */
    private $headers;

    /**
     * @var Row[]
     */
    private $rows;

    /**
     * Grid constructor.
     * @param Header[] $headers
     * @param Row[] $rows
     */
    public function __construct(array $headers, array $rows)
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}
