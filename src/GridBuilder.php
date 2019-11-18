<?php

declare(strict_types=1);

namespace Percas\Grid;


use Percas\Grid\Column\ColumnInterface;
use Percas\Grid\Column\TextColumn;
use Percas\Grid\DataSource\DataSourceInterface;
use Percas\Grid\Exception\KeyNotFoundException;

class GridBuilder
{
    /**
     * @var DataSourceInterface
     */
    private $dataSource;

    /**
     * @var ColumnInterface[]
     */
    private $columns = [];

    /**
     * GridBuilder constructor.
     * @param DataSourceInterface $dataSource
     */
    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
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
        $data = $this->dataSource->getData();

        foreach ($data as $dataRow) {
            $columns = [];

            foreach ($this->columns as $column) {
                $key = $column->getKey();

                if (!isset($dataRow[$key])) {
                    throw new KeyNotFoundException($key);
                }

                $columns[] = new DisplayColumn($key, $dataRow[$key]);
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
