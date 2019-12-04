<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


use Percas\Grid\DataFilter;
use Percas\Grid\GridState;
use Percas\Grid\Helper\PDOHelper;

class PDODataSource implements DataSourceInterface
{
    /**
     * @var \PDO
     */
    private $dbh;

    /**
     * @var string
     */
    private $object;

    /**
     * PDODataSource constructor.
     * @param \PDO $dbh
     * @param string $object
     */
    public function __construct(\PDO $dbh, string $object)
    {
        $this->dbh = $dbh;
        $this->object = $object;
    }

    /**
     * @inheritDoc
     */
    public function getData(array $columns, array $filters, GridState $state): array
    {
        $sth = $this->dbh->prepare($this->prepareQuery($columns, $filters, $state));
        $this->prepareParameters($sth, $filters, $state);

        if (!$sth->execute()) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }
        $data = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getDataCount(array $filters, GridState $state): int
    {
        $sth = $this->dbh->prepare($this->prepareQuery(['COUNT(*) AS CNT'], $filters, $state));
        $this->prepareParameters($sth, $filters, $state);

        if (!$sth->execute()) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }

        $data = $sth->fetchColumn(0);

        if ($data === false) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }

        return (int)$data;
    }

    /**
     * @param string[] $columns
     * @param DataFilter[] $filters
     * @param GridState $state
     * @return string
     */
    protected function prepareQuery(array $columns, array $filters, GridState $state): string
    {
        $cols = $this->prepareColumns($columns);
        $orderBy = $this->prepareOrderBy($state);
        $where = $this->prepareWhere($filters);
        $offsetAndLimit = $this->prepareOffsetAndLimit();

        $query = 'SELECT ' . $cols . ' FROM ' . $this->object;

        if ($where !== '') {
            $query .= ' WHERE ' . $where;
        }

        if ($orderBy !== '') {
            $query .= ' ORDER BY ' . $orderBy;
        }

        if ($offsetAndLimit !== '') {
            $query .= ' LIMIT ' . $offsetAndLimit;
        }

        return $query;
    }

    /**
     * @param string[] $columns
     * @return string
     */
    protected function prepareColumns(array $columns): string
    {
        return implode(',', $columns);
    }

    /**
     * @param DataFilter[] $filters
     * @return string
     */
    protected function prepareWhere(array $filters): string
    {
        $conditions = [];

        foreach ($filters as $filter) {
            $conditions[] = $filter->getSqlCondition();
        }

        return implode(' AND ', $conditions);
    }

    /**
     * @param GridState $state
     * @return string
     */
    protected function prepareOrderBy(GridState $state): string
    {
        return $state->isSorted() ? $state->getSortedBy() . ' ' . $state->getSortDirection() : '';
    }

    /**
     * @return string
     */
    protected function prepareOffsetAndLimit(): string
    {
        return ':_offset,:_limit';
    }

    /**
     * @param \PDOStatement $sth
     * @param DataFilter[] $filters
     * @param GridState $state
     */
    protected function prepareParameters(\PDOStatement $sth, array $filters, GridState $state): void
    {
        foreach ($filters as $filter) {
            $sth->bindValue($filter->getPlaceholder(), $filter->getValue());
        }

        $sth->bindValue(':_offset', $state->getRecordsPerPage() * ($state->getCurrentPage() - 1), \PDO::PARAM_INT);
        $sth->bindValue(':_limit', $state->getRecordsPerPage(), \PDO::PARAM_INT);
    }
}
