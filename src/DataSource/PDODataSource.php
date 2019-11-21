<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


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
    public function getData(array $columns, GridState $state): array
    {
        $sth = $this->dbh->prepare($this->prepareQuery($columns, $state));

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
     * @param GridState $state
     * @return string
     */
    protected function prepareQuery(array $columns, GridState $state): string
    {
        $cols = $this->prepareColumns($columns);
        $orderBy = $this->prepareOrderBy($state);

        $query = 'SELECT ' . $cols . ' FROM ' . $this->object;

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
     * @param GridState $state
     * @return string
     */
    protected function prepareOrderBy(GridState $state): string
    {
        return $state->isSorted() ? $state->getSortedBy() . ' ' . $state->getSortDirection() : '';
    }
}
