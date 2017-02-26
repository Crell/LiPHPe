<?php

declare(strict_types = 1);

namespace Crell\LiPHPe;


class World
{

    protected $rows = 0;

    protected $cols = 0;

    protected $current = 0;

    protected $grids = [];

    public function __construct($rows, $cols)
    {
        $this->rows = $rows;
        $this->cols = $cols;

        foreach (range(0, $rows) as $row) {
            foreach (range(0, $cols) as $col) {
                $this->grids[0][$row][$col] = new Cell('E');
                $this->grids[1][$row][$col] = new Cell('E');
            }
        }

        $this->grids[0] = $this->setGridSources($this->grids[0], $this->grids[1]);
        $this->grids[1] = $this->setGridSources($this->grids[1], $this->grids[0]);
    }

    protected function setGridSources(array $grid, array $target)
    {
        foreach ($grid as $x => $col) {
            /** @var Cell $cell */
            foreach ($col as $y => $cell) {
                $cell->setMirrorCell($target[$x][$y]);
                $cell->setSourceNeighbors($this->getCellNeighbors($target, $x, $y));
            }
        }
        return $grid;
    }

    protected function getCellNeighbors(array $target, $x, $y)
    {
        $neighbors = [];
        foreach (range(max($x - 1, 0), min($x + 1, $this->rows - 1)) as $i) {
            foreach (range(max($y - 1, 0), min($y + 1, $this->cols - 1)) as $j) {
                if (!($i == $x && $j == $y)) {
                    $neighbors[] = $target[$x][$y];
                }
            }
        }
        return $neighbors;
    }

}
