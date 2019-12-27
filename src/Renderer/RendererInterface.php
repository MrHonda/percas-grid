<?php

declare(strict_types=1);


namespace Percas\Grid\Renderer;


use Percas\Grid\Grid;

interface RendererInterface
{
    /**
     * @param Grid $grid
     * @return string
     */
    public function render(Grid $grid): string;
}
