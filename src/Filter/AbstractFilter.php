<?php

declare(strict_types=1);


namespace Percas\Grid\Filter;


abstract class AbstractFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * AbstractFilter constructor.
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
