<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\StateReader;


use Percas\Grid\GridState;
use Percas\Grid\StateReader\JsonStateReader;
use Percas\Grid\Tests\Unit\AbstractTestCase;

class JsonStateReaderTest extends AbstractTestCase
{
    public function testReadJsonFromPost(): void
    {
        $data = [
            'sorted_by' => 'col1',
            'sort_direction' => 'asc'
        ];

        $state = new GridState();
        $state
            ->setSortedBy($data['sorted_by'])
            ->setSortDirection($data['sort_direction']);

        $_POST['grid'] = json_encode($data);

        $reader = new JsonStateReader();
        $this->assertEquals($state, $reader->read());
    }

    public function testReadJsonFromGet(): void
    {
        $data = [
            'sorted_by' => 'col1',
            'sort_direction' => 'asc'
        ];

        $state = new GridState();
        $state
            ->setSortedBy($data['sorted_by'])
            ->setSortDirection($data['sort_direction']);

        $_GET['grid'] = json_encode($data);

        $reader = new JsonStateReader();
        $this->assertEquals($state, $reader->read());
    }

    public function testReadJsonFromEmptyGetAndPost(): void
    {
        $_GET = $_POST = [];

        $reader = new JsonStateReader();
        $this->assertEquals(null, $reader->read());
    }

    public function testReadJsonWithFilters(): void
    {
        $data = [
            'sorted_by' => 'col1',
            'sort_direction' => 'asc',
            'filters' => [
                1 => 'test1',
                2 => 'test2'
            ]
        ];

        $state = new GridState();
        $state
            ->setFilter(1, 'test1')
            ->setFilter(2, 'test2')
            ->setSortedBy($data['sorted_by'])
            ->setSortDirection($data['sort_direction']);

        $_GET['grid'] = json_encode($data);

        $reader = new JsonStateReader();
        $this->assertEquals($state, $reader->read());
    }
}
