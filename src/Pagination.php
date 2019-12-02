<?php

declare(strict_types=1);


namespace Percas\Grid;


class Pagination
{
    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $recordsPerPage;

    /**
     * @var int
     */
    private $recordsCount;

    /**
     * Paginator constructor.
     * @param int $currentPage
     * @param int $recordsPerPage
     * @param int $recordsCount
     */
    public function __construct(int $currentPage, int $recordsPerPage, int $recordsCount)
    {
        $this->currentPage = $currentPage;
        $this->recordsPerPage = $recordsPerPage;
        $this->recordsCount = $recordsCount;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getRecordsPerPage(): int
    {
        return $this->recordsPerPage;
    }

    /**
     * @return int
     */
    public function getRecordsCount(): int
    {
        return $this->recordsCount;
    }
}
