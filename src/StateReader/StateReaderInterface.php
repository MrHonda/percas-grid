<?php

declare(strict_types=1);


namespace Percas\Grid\StateReader;


use Percas\Grid\GridState;

interface StateReaderInterface
{
    /**
     * @return GridState|null
     */
    public function read(): ?GridState;
}
