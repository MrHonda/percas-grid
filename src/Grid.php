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
     * @var Pagination
     */
    private $pagination;

    /**
     * Grid constructor.
     * @param Header[] $headers
     * @param Row[] $rows
     * @param Pagination $pagination
     */
    public function __construct(array $headers, array $rows, Pagination $pagination)
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->pagination = $pagination;
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

    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }
}
