<?php

declare(strict_types=1);


namespace Percas\Grid\Filter;


use Percas\Grid\DataFilter;

class TextFilter extends AbstractFilter
{
    /**
     * @var string
     */
    private $value = '';

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function setValue($value): void
    {
        $this->value = (string)$value;
    }

    /**
     * @inheritDoc
     */
    public function hasValue(): bool
    {
        return $this->value !== '';
    }

    /**
     * @inheritDoc
     */
    public function getDataFilter(): DataFilter
    {
        return new DataFilter($this->key, DataFilter::OPERATOR_LIKE, $this->value);
    }
}
