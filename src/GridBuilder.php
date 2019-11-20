<?php

declare(strict_types=1);

namespace Percas\Grid;


use Percas\Grid\Column\ColumnInterface;
use Percas\Grid\Column\TextColumn;
use Percas\Grid\DataSource\DataSourceInterface;
use Percas\Grid\Exception\KeyNotFoundException;
use Percas\Grid\StateReader\JsonStateReader;
use Percas\Grid\StateReader\StateReaderInterface;

class GridBuilder
{
    /**
     * @var DataSourceInterface
     */
    private $dataSource;

    /**
     * @var string
     */
    private $primaryKey;

    /**
     * @var ColumnInterface[]
     */
    private $columns = [];

    /**
     * @var GridState
     */
    private $state;

    /**
     * @var StateReaderInterface
     */
    private $stateReader;

    /**
     * @var StateReaderInterface
     */
    private static $defaultStateReader;

    /**
     * GridBuilder constructor.
     * @param DataSourceInterface $dataSource
     * @param string $primaryKey
     */
    public function __construct(DataSourceInterface $dataSource, string $primaryKey = 'id')
    {
        $this->dataSource = $dataSource;
        $this->primaryKey = $primaryKey;

        self::$defaultStateReader = new JsonStateReader();
        $this->stateReader = self::$defaultStateReader;

        $this->initState();
    }

    /**
     * @param StateReaderInterface $defaultStateReader
     */
    public static function setDefaultStateReader(StateReaderInterface $defaultStateReader): void
    {
        self::$defaultStateReader = $defaultStateReader;
    }

    /**
     * @return Grid
     */
    public function build(): Grid
    {
        $headers = $this->extractHeaders();
        $rows = $this->getRows();

        return new Grid($headers, $rows);
    }

    /**
     * @param StateReaderInterface $stateReader
     * @return GridBuilder
     */
    public function setStateReader(StateReaderInterface $stateReader): GridBuilder
    {
        $this->stateReader = $stateReader;
        return $this;
    }

    private function initState(): void
    {
        $state = $this->stateReader->read();

        if ($state !== null) {
            $this->state = $state;
        } else {
            $this->state = new GridState();
        }
    }

    /**
     * @return Header[]
     */
    private function extractHeaders(): array
    {
        $headers = [];

        foreach ($this->columns as $column) {
            $headers[] = $column->getHeader();
        }

        return $headers;
    }

    /**
     * @return Row[]
     */
    private function getRows(): array
    {
        $rows = [];
        $data = $this->dataSource->getData($this->primaryKey, $this->columns, $this->state);

        foreach ($data as $dataRow) {
            $columns = [];

            foreach ($this->columns as $column) {
                $key = $column->getKey();

                if (!isset($dataRow[$key])) {
                    throw new KeyNotFoundException($key);
                }

                $columns[] = new DisplayColumn($key, $column->getDisplayValue($dataRow[$key]));
            }

            $rows[] = new Row($columns);
        }

        return $rows;
    }

    /**
     * @param string $key
     * @param string $name
     * @return TextColumn
     */
    public function addTextColumn(string $key, string $name): TextColumn
    {
        $column = new TextColumn($key, $name);
        $this->columns[] = $column;

        return $column;
    }
}
