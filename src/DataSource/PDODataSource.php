<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


use Percas\Grid\Column\ColumnInterface;
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
    public function getData(string $primaryKey, array $columns, GridState $state): array
    {
        $sth = $this->dbh->prepare($this->prepareQuery($primaryKey, $columns, $state));

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
     * @param string $primaryKey
     * @param array $columns
     * @param GridState $state
     * @return string
     */
    protected function prepareQuery(string $primaryKey, array $columns, GridState $state): string
    {
        $cols = $this->prepareCols($primaryKey, $columns);
        $orderBy = $this->prepareOrderBy($state);

        $query = 'SELECT ' . $cols . ' FROM ' . $this->object;

        if ($orderBy !== '') {
            $query .= ' ORDER BY ' . $orderBy;
        }

        return $query;
    }

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

            if ($key === '') {
                continue;
            }

            if (!in_array($key, $cols, true)) {
                $cols[] = $key;
            }
        }

        return implode(',', $cols);
    }

    /**
     * @param GridState $state
     * @return string
     */
    protected function prepareOrderBy(GridState $state): string
    {
        $sortedBy = $state->getSortedBy();
        $sortDirection = strtoupper($state->getSortDirection());

        if ($sortedBy === '' || $sortDirection === '') {
            return '';
        }

        if ($sortDirection !== 'DESC') {
            $sortDirection = 'ASC';
        }

        return $sortedBy . ' ' . $sortDirection;
    }
}
