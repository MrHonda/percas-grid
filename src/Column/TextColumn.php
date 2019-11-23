<?php

declare(strict_types=1);


namespace Percas\Grid\Column;


use Percas\Grid\Filter\TextFilter;

class TextColumn extends AbstractColumn
{
    /**
     * @inheritDoc
     */
    public function getDefaultFilters(): array
    {
        return [new TextFilter($this->key)];
    }

}
