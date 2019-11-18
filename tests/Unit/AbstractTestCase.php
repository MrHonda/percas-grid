<?php

declare(strict_types=1);

namespace Percas\Grid\Tests\Unit;


use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }
}
