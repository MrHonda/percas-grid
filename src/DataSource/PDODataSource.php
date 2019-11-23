<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


use Percas\Grid\DataFilter;
use Percas\Grid\GridState;

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
        $this->prepareParameters($sth, $filters);

        if (!$sth->execute()) {
            throw new \PDOException($this->getErrorMessage($this->dbh->errorInfo()));
        }
        $data = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new \PDOException($this->getErrorMessage($this->dbh->errorInfo()));
        }

        return $data;
    }

    /**
     * @param array $errorInfo
     * @return string
     */
    private function getErrorMessage(array $errorInfo): string
    {
        return 'ERROR ' . $errorInfo[1] . ' (' . $errorInfo[0] . '): ' . $errorInfo[2];
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

        $query = 'SELECT ' . $cols . ' FROM ' . $this->object;

        if ($where !== '') {
            $query .= ' WHERE ' . $where;
        }

        if ($orderBy !== '') {
            $query .= ' ORDER BY ' . $orderBy;
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
     * @param \PDOStatement $sth
     * @param DataFilter[] $filters
     */
    protected function prepareParameters(\PDOStatement $sth, array $filters): void
    {
        foreach ($filters as $filter) {
            $sth->bindValue($filter->getPlaceholder(), $filter->getValue());
        }
    }
}
