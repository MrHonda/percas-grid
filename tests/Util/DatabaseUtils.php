<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Util;


class DatabaseUtils
{
    /**
     * @return \PDO
     */
    public static function setUpDatabase(): \PDO
    {
        $dbh = self::createConnection();
        self::createGridStateStructure($dbh);
        self::createTestStructure($dbh);

        return $dbh;
    }

    /**
     * @return \PDO
     */
    public static function createConnection(): \PDO
    {
        $dbh = new \PDO('sqlite::memory:');
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $dbh;
    }

    /**
     * @param \PDO $dbh
     */
    public static function createGridStateStructure(\PDO $dbh): void
    {
        $dbh->exec('
            CREATE TABLE grid_state (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              grid_identifier VARCHAR(30) NOT NULL,
              user_identifier VARCHAR(30) NOT NULL,
              sorted_by VARCHAR(30) DEFAULT NULL,
              sort_direction VARCHAR(3) DEFAULT NULL,
              current_page INT(11) NOT NULL,
              records_per_page INT(11) DEFAULT NULL,
              filter1 VARCHAR(255) DEFAULT NULL,
              filter2 VARCHAR(255) DEFAULT NULL,
              filter3 VARCHAR(255) DEFAULT NULL,
              filter4 VARCHAR(255) DEFAULT NULL,
              filter5 VARCHAR(255) DEFAULT NULL
            );
        ');
    }

    /**
     * @param \PDO $dbh
     */
    public static function createTestStructure(\PDO $dbh): void
    {
        $dbh->exec('
            CREATE TABLE grid1 (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              value1 VARCHAR(50) DEFAULT NULL,
              value2 VARCHAR(50) DEFAULT NULL,
              value3 VARCHAR(50) DEFAULT NULL
            );
        ');

        $dbh->exec("
            INSERT INTO grid1 (value1, value2, value3) VALUES 
            ('val 1', 'val 2', 'val 3'),
            ('val 2', 'val 3', 'val 4');
        ");
    }
}
