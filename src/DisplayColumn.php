<?php

declare(strict_types=1);


namespace Percas\Grid;


class DisplayColumn
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * DisplayColumn constructor.
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
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
    public function getValue(): string
    {
        return $this->value;
    }
}
