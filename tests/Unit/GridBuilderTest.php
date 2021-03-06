<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit;


use Percas\Grid\DataSource\DataSourceInterface;
use Percas\Grid\DisplayColumn;
use Percas\Grid\Exception\KeyNotFoundException;
use Percas\Grid\Grid;
use Percas\Grid\GridBuilder;
use Percas\Grid\GridState;
use Percas\Grid\Header;
use Percas\Grid\Pagination;
use Percas\Grid\Row;
use Percas\Grid\StateReader\StateReaderInterface;

class GridBuilderTest extends AbstractTestCase
{
    private function createGridBuilder(): GridBuilder
    {
        $dataSource = \Mockery::mock(DataSourceInterface::class);
        $dataSource->shouldReceive('getData')->andReturns($this->getSampleData())->once();
        $dataSource->shouldReceive('getDataCount')->andReturns(3)->atMost()->once();
        return new GridBuilder($dataSource);
    }

    private function getSampleData(): array
    {
        return [
            ['name' => 'John Doe', 'nickname' => 'jdoe', 'number' => 1],
            ['name' => 'Penny Tool', 'nickname' => 'ptool', 'number' => 2],
            ['name' => 'Max Conversion', 'nickname' => 'mcon', 'number' => 3],
        ];
    }

    private function createSampleGrid(): Grid
    {
        $data = $this->getSampleData();
        $headers = [];
        $rows = [];
        $headersSet = false;

        foreach ($data as $dataRow) {
            $columns = [];

            foreach ($dataRow as $key => $value) {
                if (!$headersSet) {
                    $header = new Header($key, $key);
                    $header->setFilterable(false);
                    $headers[] = $header;
                }
                $columns[] = new DisplayColumn($key, (string)$value);
            }

            $headersSet = true;
            $rows[] = new Row($columns);
        }

        return new Grid($headers, $rows, new Pagination(1, 10, 3));
    }

    public function testSimpleGridDefinition(): void
    {
        $builder = $this->createGridBuilder();
        $builder->addTextColumn('name', 'name')->setFilterable(false);
        $builder->addTextColumn('nickname', 'nickname')->setFilterable(false);
        $builder->addTextColumn('number', 'number')->setFilterable(false);

        $this->assertEquals($this->createSampleGrid(), $builder->build());
    }

    public function testNotExistingColumnKey(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $builder = $this->createGridBuilder();
        $builder->addTextColumn('namee', 'name');
        $builder->build();
    }

    public function testGridWithAppliedSort(): void
    {
        $state = new GridState();
        $state
            ->setSortedBy('id')
            ->setSortDirection('desc');

        $stateReader = \Mockery::mock(StateReaderInterface::class);
        $stateReader->shouldReceive('read')->andReturn($state);

        $returnData = [
            ['id' => 2],
            ['id' => 1],
        ];

        $dataSource = \Mockery::mock(DataSourceInterface::class);
        $dataSource->shouldReceive('getData')->with(['id'], [], $state)->andReturns($returnData)->once();
        $dataSource->shouldReceive('getDataCount')->andReturns(2)->once();

        $builder = new GridBuilder($dataSource);
        $builder->setStateReader($stateReader);

        $builder->addTextColumn('id', 'id')->setFilterable(false);

        $grid = $builder->build();

        $rows = [
            new Row([new DisplayColumn('id', '2')]),
            new Row([new DisplayColumn('id', '1')]),
        ];

        $this->assertEquals($rows, $grid->getRows());
    }

    public function testGridWithAppliedFilter(): void
    {
        $state = new GridState();
        $state
            ->setFilter(1, 'val 1');

        $stateReader = \Mockery::mock(StateReaderInterface::class);
        $stateReader->shouldReceive('read')->andReturn($state);

        $returnData = [
            ['value1' => 'val 1'],
        ];

        //Because of internal incrementing of the index in DataFilter class, you can't use "with" method
        $dataSource = \Mockery::mock(DataSourceInterface::class);
        $dataSource
            ->shouldReceive('getData')
            ->andReturns($returnData)
            ->once();
        $dataSource
            ->shouldReceive('getDataCount')
            ->andReturns(1)
            ->once();

        $builder = new GridBuilder($dataSource);
        $builder->setStateReader($stateReader);

        $builder->addTextColumn('value1', 'value1');

        $grid = $builder->build();

        $rows = [
            new Row([new DisplayColumn('value1', 'val 1')])
        ];

        $this->assertEquals($rows, $grid->getRows());
    }

    public function testPaginationGeneration(): void
    {
        $builder = $this->createGridBuilder();
        $grid = $builder->build();
        $this->assertEquals(new Pagination(1, GridState::DEFAULT_RECORDS_PER_PAGE, 3), $grid->getPagination());
    }
}
