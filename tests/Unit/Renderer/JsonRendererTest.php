<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\Renderer;


use Percas\Grid\DisplayColumn;
use Percas\Grid\Filter\TextFilter;
use Percas\Grid\Grid;
use Percas\Grid\Header;
use Percas\Grid\Pagination;
use Percas\Grid\Renderer\JsonRenderer;
use Percas\Grid\Row;
use Percas\Grid\Tests\Unit\AbstractTestCase;

class JsonRendererTest extends AbstractTestCase
{
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
                    $header->addFilter(new TextFilter($key));
                    $headers[] = $header;
                }
                $columns[] = new DisplayColumn($key, (string)$value);
            }

            $headersSet = true;
            $rows[] = new Row($columns);
        }

        return new Grid($headers, $rows, new Pagination(1, 10, 3));
    }

    public function testRenderer(): void
    {
        $renderer = new JsonRenderer();
        $grid = $this->createSampleGrid();
        $json = '{"headers":[{"key":"name","name":"name","sortable":true,"filterable":true,"filters":[{"value":"","key":"name"}]},{"key":"nickname","name":"nickname","sortable":true,"filterable":true,"filters":[{"value":"","key":"nickname"}]},{"key":"number","name":"number","sortable":true,"filterable":true,"filters":[{"value":"","key":"number"}]}],"rows":[{"columns":[{"key":"name","value":"John Doe"},{"key":"nickname","value":"jdoe"},{"key":"number","value":"1"}]},{"columns":[{"key":"name","value":"Penny Tool"},{"key":"nickname","value":"ptool"},{"key":"number","value":"2"}]},{"columns":[{"key":"name","value":"Max Conversion"},{"key":"nickname","value":"mcon"},{"key":"number","value":"3"}]}],"pagination":{"currentPage":1,"recordsPerPage":10,"recordsCount":3}}';
        $this->assertEquals($json, $renderer->render($grid));
    }
}
