<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\DataSource;


use Percas\Grid\DataSource\PDODataSource;
use Percas\Grid\GridState;
use Percas\Grid\Tests\Unit\AbstractTestCase;
use Percas\Grid\Tests\Util\TestUtils;

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
        $columns = ['value1', 'value2'];

        $this->assertIsArray($this->dataSource->getData($columns, new GridState()));
    }

    public function testGetDataWithNonExistingColumn(): void
    {
        $this->expectException(\PDOException::class);
        $columns = ['value1', 'vaalue2'];

        $this->assertIsArray($this->dataSource->getData($columns, new GridState()));
    }

    public function testGetDataWithNonExistingObject(): void
    {
        $this->expectException(\PDOException::class);

        $dataSource = new PDODataSource(self::$dbh, 'grid123');
        $dataSource->getData([], new GridState());
    }

    /**
     * @throws \ReflectionException
     */
    public function testQueryGenerationWithoutOrderBy(): void
    {
        $columns = ['id', 'value1'];
        $state = new GridState();

        $result = TestUtils::invokeMethod($this->dataSource, 'prepareQuery', [$columns, $state]);
        $this->assertEquals('SELECT id,value1 FROM grid1', $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testQueryGenerationWithOrderBy(): void
    {
        $columns = ['id', 'value1'];
        $state = new GridState();
        $state
            ->setSortedBy('id')
            ->setSortDirection('desc');

        $result = TestUtils::invokeMethod($this->dataSource, 'prepareQuery', [$columns, $state]);
        $this->assertEquals('SELECT id,value1 FROM grid1 ORDER BY id DESC', $result);
    }
}
