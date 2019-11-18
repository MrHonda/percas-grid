<?php

declare(strict_types=1);

namespace Percas\Grid\Column;


use Percas\Grid\Header;

interface ColumnInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return Header
     */
    public function getHeader(): Header;
}
