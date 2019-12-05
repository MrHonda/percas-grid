<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\DataSource;


use Percas\Grid\DataFilter;
use Percas\Grid\DataSource\PDODataSource;
use Percas\Grid\GridState;
use Percas\Grid\Tests\Unit\AbstractTestCase;
use Percas\Grid\Tests\Util\DatabaseUtils;
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
        self::$dbh = DatabaseUtils::setUpDatabase();
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

        $this->assertIsArray($this->dataSource->getData($columns, [], new GridState()));
    }

    public function testGetDataWithNonExistingColumn(): void
    {
        $this->expectException(\PDOException::class);
        $columns = ['value1', 'vaalue2'];

        $this->assertIsArray($this->dataSource->getData($columns, [], new GridState()));
    }

    public function testGetDataWithNonExistingObject(): void
    {
        $this->expectException(\PDOException::class);

        $dataSource = new PDODataSource(self::$dbh, 'grid123');
        $dataSource->getData([], [], new GridState());
    }

    /**
     * @throws \ReflectionException
     */
    public function testQueryGenerationWithoutOrderBy(): void
    {
        $columns = ['id', 'value1'];
        $state = new GridState();

        $result = TestUtils::invokeMethod($this->dataSource, 'prepareQuery', [$columns, [], $state]);
        $this->assertEquals('SELECT id,value1 FROM grid1 LIMIT :_offset,:_limit', $result);
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

        $result = TestUtils::invokeMethod($this->dataSource, 'prepareQuery', [$columns, [], $state]);
        $this->assertEquals('SELECT id,value1 FROM grid1 ORDER BY id DESC LIMIT :_offset,:_limit', $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testQueryGenerationWithWhere(): void
    {
        $columns = ['id', 'value1'];
        $state = new GridState();
        $state
            ->setFilter(1, 'test');

        $filter = new DataFilter('value1', '=', 'test');
        $where = $filter->getSqlCondition();

        $result = TestUtils::invokeMethod($this->dataSource, 'prepareQuery', [$columns, [$filter], $state]);
        $this->assertEquals('SELECT id,value1 FROM grid1 WHERE ' . $where . ' LIMIT :_offset,:_limit', $result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testQueryGenerationWithWhereAndOrderBy(): void
    {
        $columns = ['id', 'value1'];
        $state = new GridState();
        $state
            ->setFilter(1, 'test')
            ->setSortedBy('id')
            ->setSortDirection('desc');

        $filter = new DataFilter('value1', '=', 'test');
        $where = $filter->getSqlCondition();

        $result = TestUtils::invokeMethod($this->dataSource, 'prepareQuery', [$columns, [$filter], $state]);
        $this->assertEquals('SELECT id,value1 FROM grid1 WHERE ' . $where . ' ORDER BY id DESC LIMIT :_offset,:_limit', $result);
    }

    public function testGetDataCount(): void
    {
        $this->assertEquals(2, $this->dataSource->getDataCount([], new GridState()));
    }
}
