<?php

declare(strict_types=1);


namespace Percas\Grid\Helper;


class PDOHelper
{
    /**
     * PDOHelper constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param mixed[] $errorInfo
     * @return string
     */
    public static function getErrorMessage(array $errorInfo): string
    {
        return 'ERROR ' . $errorInfo[1] . ' (' . $errorInfo[0] . '): ' . $errorInfo[2];
    }
}
