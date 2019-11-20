<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


class PDODataSource extends AbstractDatabaseDataSource
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
    public function getData(string $primaryKey, array $columns): array
    {
        $cols = $this->prepareCols($primaryKey, $columns);

        $sth = $this->dbh->prepare('SELECT ' . $cols . ' FROM ' . $this->object);

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
}
