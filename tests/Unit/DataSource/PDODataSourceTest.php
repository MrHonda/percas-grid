<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\DataSource;


use Percas\Grid\Column\TextColumn;
use Percas\Grid\DataSource\PDODataSource;
use Percas\Grid\Tests\Unit\AbstractTestCase;

class PDODataSourceTest extends AbstractTestCase
{
    /**
     * @var \PDO
     */
    private static $dbh;

    /**
     * @var PDODataSource
     */
    private $dataSource;

    public static function setUpBeforeClass(): void
    {
        //TODO: Change it to localhost?
        self::$dbh = new \PDO('mysql:host=192.168.1.137:3307;dbname=percas_grid', 'root', 'root');
        self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh = null;
    }

    protected function setUp(): void
    {
        $this->dataSource = new PDODataSource(self::$dbh, 'grid1');
    }

    public function testGetData(): void
    {
        $columns = [
            new TextColumn('value1', 'value1'),
            new TextColumn('value2', 'value2'),
        ];

        $this->assertIsArray($this->dataSource->getData('id', $columns));
    }

    public function testGetDataWithNonExistingColumn(): void
    {
        $this->expectException(\PDOException::class);

        $columns = [
            new TextColumn('value1', 'value1'),
            new TextColumn('vaalue2', 'value2'),
        ];

        $this->assertIsArray($this->dataSource->getData('id', $columns));
    }

    public function testGetDataWithNonExistingObject(): void
    {
        $this->expectException(\PDOException::class);

        $dataSource = new PDODataSource(self::$dbh, 'grid123');
        $dataSource->getData('id', []);
    }
}
