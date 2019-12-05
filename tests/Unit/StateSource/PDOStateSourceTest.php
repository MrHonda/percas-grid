<?php

declare(strict_types=1);


namespace Percas\Grid\Tests\Unit\StateSource;


use Percas\Grid\GridState;
use Percas\Grid\StateSource\PDOStateSource;
use Percas\Grid\Tests\Unit\AbstractTestCase;
use Percas\Grid\Tests\Util\DatabaseUtils;

class PDOStateSourceTest extends AbstractTestCase
{
    /**
     * @var \PDO
     */
    private static $dbh;

    private function getSampleState(): GridState
    {
        $state = new GridState();
        $state
            ->setCurrentPage(2)
            ->setRecordsPerPage(20)
            ->setSortedBy('col')
            ->setSortDirection(GridState::SORT_ASC)
            ->setFilter(1, 'filter1')
            ->setFilter(2, 'filter2');

        return $state;
    }

    private function getSampleStateAsArray(): array
    {
        return [
            'id' => '1',
            'grid_identifier' => 'grid',
            'user_identifier' => 'test',
            'sorted_by' => 'col',
            'sort_direction' => GridState::SORT_ASC,
            'current_page' => '2',
            'records_per_page' => '20',
            'filter1' => 'filter1',
            'filter2' => 'filter2',
            'filter3' => NULL,
            'filter4' => NULL,
            'filter5' => NULL,
        ];
    }

    public static function setUpBeforeClass(): void
    {
        self::$dbh = DatabaseUtils::setUpDatabase();
    }

    public function testInsertSaveAndLoad(): void
    {
        $source = new PDOStateSource(self::$dbh);
        $state = $this->getSampleState();

        $source->save('grid', 'test', $state);

        $sth = self::$dbh->query('SELECT * FROM grid_state');

        $this->assertEquals($this->getSampleStateAsArray(), $sth->fetch(\PDO::FETCH_ASSOC));
        $this->assertEquals($state, $source->load('grid', 'test'));
    }

    public function testUpdateSave(): void
    {
        $source = new PDOStateSource(self::$dbh);
        $state = $this->getSampleState();

        $source->save('grid', 'test', $state);

        $state
            ->setCurrentPage(2)
            ->setFilter(1, 'new filter1');

        $source->save('grid', 'test', $state);

        $this->assertEquals($state, $source->load('grid', 'test'));
    }
}
