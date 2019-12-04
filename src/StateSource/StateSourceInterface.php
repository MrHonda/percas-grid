<?php

declare(strict_types=1);


namespace Percas\Grid\StateSource;


use Percas\Grid\GridState;

interface StateSourceInterface
{
    /**
     * @param string|int $identifier
     * @return GridState
     */
    public function load($identifier): GridState;

    /**
     * @param string|int $identifier
     * @param GridState $state
     */
    public function save($identifier, GridState $state): void;
}
