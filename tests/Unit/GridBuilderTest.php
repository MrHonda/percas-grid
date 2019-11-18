<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit;


use Percas\Grid\DataSource\DataSourceInterface;
use Percas\Grid\DisplayColumn;
use Percas\Grid\Exception\KeyNotFoundException;
use Percas\Grid\Grid;
use Percas\Grid\GridBuilder;
use Percas\Grid\Header;
use Percas\Grid\Row;

class GridBuilderTest extends AbstractTestCase
{
    public function testSimpleGridDefinition(): void
    {
        $builder = $this->createGridBuilder();
        $builder->addTextColumn('name', 'name');
        $builder->addTextColumn('nickname', 'nickname');
        $builder->addTextColumn('number', 'number');

        $this->assertEquals($this->createSampleGrid(), $builder->build());
    }

    private function createGridBuilder(): GridBuilder
    {
        $dataSource = \Mockery::mock(DataSourceInterface::class);
        $dataSource->shouldReceive('getData')->andReturns($this->getSampleData())->once();
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
                    $headers[] = new Header($key, $key);
                }
                $columns[] = new DisplayColumn($key, $value);
            }

            $headersSet = true;
            $rows[] = new Row($columns);
        }

        return new Grid($headers, $rows);
    }

    public function testNotExistingColumnKey(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $builder = $this->createGridBuilder();
        $builder->addTextColumn('namee', 'name');
        $builder->build();
    }
}
