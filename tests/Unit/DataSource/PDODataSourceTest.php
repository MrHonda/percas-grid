<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\DataSource;


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
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh = null;
    }

    public function testGetData(): void
    {
        $this->assertIsArray($this->dataSource->getData());
    }

    public function testGetDataWithWrongSqlSyntax(): void
    {
        $this->expectException(\PDOException::class);

        $dataSource = new PDODataSource(self::$dbh->prepare('SELECT * FROMm grid1'));
        $dataSource->getData();
    }

    protected function setUp(): void
    {
        $this->dataSource = new PDODataSource(self::$dbh->prepare('SELECT * FROM grid1'));
    }
}
