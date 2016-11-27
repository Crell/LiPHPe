<?php

declare(strict_types = 1);

namespace Crell\LiPHPe;


class Cell
{

    protected $state;

    protected $neighbors = [];

    public function __construct($start, Cell $mirror = null)
    {
        $this->state = $start;
    }

    public function setSourceNeighbors(array $neighbors)
    {
        $this->neighbors = $neighbors;
    }

    public function getState()
    {
        return $this->state;
    }

    public function updateValue()
    {

    }
}
