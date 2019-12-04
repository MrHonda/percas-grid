<?php

declare(strict_types=1);

namespace Percas\Grid;


use Percas\Grid\Column\ColumnInterface;
use Percas\Grid\Column\TextColumn;
use Percas\Grid\DataSource\DataSourceInterface;
use Percas\Grid\Exception\KeyNotFoundException;
use Percas\Grid\StateReader\JsonStateReader;
use Percas\Grid\StateReader\StateReaderInterface;
use Percas\Grid\StateSource\StateSourceInterface;

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
     * @var string|int
     */
    private $stateIdentifier;

    /**
     * @var StateReaderInterface
     */
    private $stateReader;

    /**
     * @var StateSourceInterface
     */
    private $stateSource;

    /**
     * @var string|int
     */
    private static $defaultStateIdentifier = '';

    /**
     * @var StateReaderInterface
     */
    private static $defaultStateReader;

    /**
     * @var StateSourceInterface
     */
    private static $defaultStateSource;

    /**
     * GridBuilder constructor.
     * @param DataSourceInterface $dataSource
     * @param string $primaryKey
     */
    public function __construct(DataSourceInterface $dataSource, string $primaryKey = 'id')
    {
        $this->dataSource = $dataSource;
        $this->primaryKey = $primaryKey;

        if (self::$defaultStateReader === null) {
            self::$defaultStateReader = new JsonStateReader();
        }

        $this->stateIdentifier = self::$defaultStateIdentifier;
        $this->stateReader = self::$defaultStateReader;
        $this->stateSource = self::$defaultStateSource;
    }

    /**
     * @param string|int $defaultStateIdentifier
     */
    public static function setDefaultStateIdentifier($defaultStateIdentifier): void
    {
        self::$defaultStateIdentifier = $defaultStateIdentifier;
    }

    /**
     * @param StateReaderInterface $defaultStateReader
     */
    public static function setDefaultStateReader(StateReaderInterface $defaultStateReader): void
    {
        self::$defaultStateReader = $defaultStateReader;
    }

    /**
     * @param StateSourceInterface $defaultStateSource
     */
    public static function setDefaultStateSource(StateSourceInterface $defaultStateSource): void
    {
        self::$defaultStateSource = $defaultStateSource;
    }

    /**
     * @return Grid
     */
    public function build(): Grid
    {
        $this->initState();

        $headers = $this->extractHeaders();
        $filters = $this->prepareFilters($headers);

        $rows = $this->getRows($filters);
        $pagination = new Pagination($this->state->getCurrentPage(), $this->state->getRecordsPerPage(), $this->dataSource->getDataCount($filters, $this->state));

        if ($this->stateSource !== null) {
            $this->stateSource->save($this->stateIdentifier, $this->state);
        }

        return new Grid($headers, $rows, $pagination);
    }

    /**
     * @param string|int $stateIdentifier
     * @return GridBuilder
     */
    public function setStateIdentifier($stateIdentifier): GridBuilder
    {
        $this->stateIdentifier = $stateIdentifier;
        return $this;
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

    /**
     * @param StateSourceInterface $stateSource
     * @return GridBuilder
     */
    public function setStateSource(StateSourceInterface $stateSource): GridBuilder
    {
        $this->stateSource = $stateSource;
        return $this;
    }

    private function initState(): void
    {
        $state = $this->stateReader->read();

        if ($state !== null) {
            $this->state = $state;
        } else if ($this->stateSource !== null) {
            $this->state = $this->stateSource->load($this->stateIdentifier);
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
     * @return string[]
     */
    private function prepareKeys(): array
    {
        $keys = [];
        $keys[] = $this->primaryKey;

        foreach ($this->columns as $column) {
            $key = $column->getKey();
            if ($key === '') {
                continue;
            }

            if (!in_array($key, $keys, true)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * @param Header[] $headers
     * @return DataFilter[]
     */
    private function prepareFilters(array $headers): array
    {
        $filters = [];
        $index = 1;

        foreach ($headers as $header) {
            foreach ($header->getFilters() as $filter) {
                $filter->setValue($this->state->getFilter($index++));

                if ($filter->hasValue()) {
                    $filters[] = $filter->getDataFilter();
                }
            }
        }

        return $filters;
    }

    /**
     * @param DataFilter[] $filters
     * @return Row[]
     */
    private function getRows(array $filters): array
    {
        $rows = [];
        $data = $this->dataSource->getData($this->prepareKeys(), $filters, $this->state);

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
     * @param ColumnInterface $column
     * @return ColumnInterface
     */
    public function addColumn(ColumnInterface $column): ColumnInterface
    {
        $this->columns[] = $column;
        return $column;
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
