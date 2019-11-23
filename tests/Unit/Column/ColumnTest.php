<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\Column;


use Percas\Grid\Column\TextColumn;
use Percas\Grid\Filter\TextFilter;
use Percas\Grid\Tests\Unit\AbstractTestCase;

class ColumnTest extends AbstractTestCase
{
    public function testDefaultFiltersAssignement(): void
    {
        $column = new TextColumn('test', 'test');

        $this->assertEquals([new TextFilter('test')], $column->getHeader()->getFilters());
    }

    public function testNoFilterableColumn(): void
    {
        $column = new TextColumn('test', 'test');
        $column->setFilterable(false);

        $this->assertEquals([], $column->getHeader()->getFilters());
    }

    public function testCustomFiltersAssignementViaAddFilterMethod(): void
    {
        $filter1 = new TextFilter('filter1');
        $filter2 = new TextFilter('filter2');

        $column = new TextColumn('test', 'test');
        $column
            ->addFilter($filter1)
            ->addFilter($filter2);

        $this->assertEquals([$filter1, $filter2], $column->getHeader()->getFilters());
    }

    public function testCustomFiltersAssignementViaSetFiltersMethod(): void
    {
        $filter1 = new TextFilter('filter1');
        $filter2 = new TextFilter('filter2');

        $column = new TextColumn('test', 'test');
        $column
            ->addFilter(new TextFilter('filter3'))
            ->setFilters([$filter1, $filter2]);

        $this->assertEquals([$filter1, $filter2], $column->getHeader()->getFilters());
    }
}
