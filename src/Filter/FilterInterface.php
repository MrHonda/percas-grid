<?php

declare(strict_types=1);


namespace Percas\Grid\Filter;


use Percas\Grid\DataFilter;

interface FilterInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     */
    public function setValue($value): void;

    /**
     * @return bool
     */
    public function hasValue(): bool;

    /**
     * @return DataFilter
     */
    public function getDataFilter(): DataFilter;
}
