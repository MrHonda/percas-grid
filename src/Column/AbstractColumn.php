<?php

declare(strict_types=1);


namespace Percas\Grid\Column;


use Percas\Grid\Header;

abstract class AbstractColumn implements ColumnInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var Header
     */
    protected $header;

    /**
     * TextColumn constructor.
     * @param string $key
     * @param string $name
     */
    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->header = new Header($key, $name);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayValue($value): string
    {
        return (string)$value;
    }

    /**
     * @inheritDoc
     */
    public function setSortable(bool $sortable)
    {
        $this->header->setSortable($sortable);
        return $this;
    }
}
