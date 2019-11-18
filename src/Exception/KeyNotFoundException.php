<?php

declare(strict_types=1);


namespace Percas\Grid\Exception;


use Throwable;

class KeyNotFoundException extends \RuntimeException
{
    /**
     * KeyNotFoundException constructor.
     * @param string $key
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $key, int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Key [' . $key . '] not found', $code, $previous);
    }
}
