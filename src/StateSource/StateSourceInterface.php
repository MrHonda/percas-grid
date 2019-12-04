<?php

declare(strict_types=1);


namespace Percas\Grid\StateSource;


use Percas\Grid\GridState;

interface StateSourceInterface
{
    /**
     * @param string|int $gridIdentifier
     * @param string|int $userIdentifier
     * @return GridState
     */
    public function load($gridIdentifier, $userIdentifier): GridState;

    /**
     * @param string|int $gridIdentifier
     * @param string|int $userIdentifier
     * @param GridState $state
     */
    public function save($gridIdentifier, $userIdentifier, GridState $state): void;
}
