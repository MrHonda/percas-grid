<?php

declare(strict_types=1);


namespace Percas\Grid;


class Header
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $sortable = true;

    /**
     * Header constructor.
     * @param string $key
     * @param string $name
     */
    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     * @return Header
     */
    public function setSortable(bool $sortable): Header
    {
        $this->sortable = $sortable;
        return $this;
    }
}
