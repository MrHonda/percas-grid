<?php

declare(strict_types=1);


namespace Percas\Grid\DataSource;


class PDODataSource implements DataSourceInterface
{
    /**
     * @var \PDOStatement
     */
    private $sth;

    /**
     * PDODataSource constructor.
     * @param \PDOStatement $sth
     */
    public function __construct(\PDOStatement $sth)
    {
        $this->sth = $sth;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        if (!$this->sth->execute()) {
            throw new \PDOException($this->getErrorMessage($this->sth->errorInfo()));
        }
        $data = $this->sth->fetchAll(\PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new \PDOException($this->getErrorMessage($this->sth->errorInfo()));
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
