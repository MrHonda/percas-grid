<?php

declare(strict_types=1);


namespace Percas\Grid\StateSource;


use Percas\Grid\GridState;
use Percas\Grid\Helper\PDOHelper;

class PDOStateSource implements StateSourceInterface
{
    /**
     * @var \PDO
     */
    private $dbh;

    /**
     * @var string
     */
    private $table;

    /**
     * PDOStateSource constructor.
     * @param \PDO $dbh
     * @param string $table
     */
    public function __construct(\PDO $dbh, string $table = 'grid_state')
    {
        $this->dbh = $dbh;
        $this->table = $table;
    }

    /**
     * @inheritDoc
     */
    public function load($gridIdentifier, $userIdentifier): GridState
    {
        $sth = $this->dbh->prepare('SELECT * FROM ' . $this->table . ' WHERE grid_identifier = :grid_identifier AND user_identifier = :user_identifier');
        $sth->bindValue(':grid_identifier', $gridIdentifier, is_int($gridIdentifier) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        $sth->bindValue(':user_identifier', $userIdentifier, is_int($userIdentifier) ? \PDO::PARAM_INT : \PDO::PARAM_STR);

        if (!$sth->execute()) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }

        $data = $sth->fetch();

        $state = new GridState();

        if ($data !== false && count($data) > 0) {
            $state
                ->setSortedBy($data['sorted_by'])
                ->setSortDirection($data['sort_direction'])
                ->setCurrentPage((int)$data['current_page'])
                ->setRecordsPerPage((int)$data['records_per_page']);

            $index = 1;

            while ($index < 9999) {
                $key = 'filter' . $index;

                if (!isset($data[$key])) {
                    break;
                }

                $state->setFilter($index, $data[$key]);
                $index++;
            }
        }

        return $state;
    }

    /**
     * @inheritDoc
     */
    public function save($gridIdentifier, $userIdentifier, GridState $state): void
    {
        if ($this->checkIfStateExists($gridIdentifier, $userIdentifier)) {
            $sth = $this->prepareUpdate($state);
        } else {
            $sth = $this->prepareInsert($state);
        }

        $sth->bindValue(':grid_identifier', $gridIdentifier, is_int($gridIdentifier) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        $sth->bindValue(':user_identifier', $userIdentifier, is_int($userIdentifier) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        $sth->bindValue(':sorted_by', $state->getSortedBy());
        $sth->bindValue(':sort_direction', $state->getSortDirection());
        $sth->bindValue(':current_page', $state->getCurrentPage(), \PDO::PARAM_INT);
        $sth->bindValue(':records_per_page', $state->getRecordsPerPage(), \PDO::PARAM_INT);

        foreach ($state->getFilters() as $index => $filter) {
            $sth->bindValue(':filter' . $index, $filter);
        }

        if (!$sth->execute()) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }
    }

    /**
     * @param string|int $gridIdentifier
     * @param string|int $userIdentifier
     * @return bool
     */
    private function checkIfStateExists($gridIdentifier, $userIdentifier): bool
    {
        $sth = $this->dbh->prepare('SELECT id FROM ' . $this->table . ' WHERE grid_identifier = :grid_identifier AND user_identifier = :user_identifier');
        $sth->bindValue(':grid_identifier', $gridIdentifier, is_int($gridIdentifier) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        $sth->bindValue(':user_identifier', $userIdentifier, is_int($userIdentifier) ? \PDO::PARAM_INT : \PDO::PARAM_STR);

        if (!$sth->execute()) {
            throw new \PDOException(PDOHelper::getErrorMessage($this->dbh->errorInfo()));
        }

        return $sth->fetchColumn(0) !== false;
    }

    /**
     * @param GridState $state
     * @return \PDOStatement
     */
    private function prepareInsert(GridState $state): \PDOStatement
    {
        $filterIndexes = array_keys($state->getFilters());
        $query = 'INSERT INTO ' . $this->table . ' (grid_identifier,user_identifier,sorted_by,sort_direction,current_page,records_per_page';

        foreach ($filterIndexes as $index) {
            $query .= ',filter' . $index;
        }

        $query .= ') VALUES (:grid_identifier,:user_identifier,:sorted_by,:sort_direction,:current_page,:records_per_page';

        foreach ($filterIndexes as $index) {
            $query .= ',:filter' . $index;
        }

        $query .= ')';

        return $this->dbh->prepare($query);
    }

    /**
     * @param GridState $state
     * @return \PDOStatement
     */
    private function prepareUpdate(GridState $state): \PDOStatement
    {
        $filterIndexes = array_keys($state->getFilters());
        $query = 'UPDATE ' . $this->table . ' SET sorted_by = :sorted_by, sort_direction = :sort_direction, current_page = :current_page, records_per_page = :records_per_page';

        foreach ($filterIndexes as $index) {
            $query .= ', filter' . $index . ' = :filter' . $index;
        }

        $query .= ' WHERE grid_identifier = :grid_identifier AND user_identifier = :user_identifier';

        return $this->dbh->prepare($query);
    }
}
