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

    public function cellAt($x, $y)
    {
        return $this->getActiveGrid()[$x][$y];
    }

    public function place($state, $x, $y)
    {
        $grid = $this->getActiveGrid();
        $grid[$x][$y]->setState($state);

        return $this;
    }

    public function step()
    {
        // Update all cell values in the inactive grid.

        /** @var Cell $cell */
        foreach ($this->cellIterator($this->getInactiveGrid()) as $coord => $cell) {
            $cell->updateValue();
        }

        // Now set that grid active.
        $this->current = ($this->current + 1) % 2;

        return;
    }

    protected function cellIterator(array $grid)
    {
        foreach ($grid as $x => $col) {
            /** @var Cell $cell */
            foreach ($col as $y => $cell) {
                yield ['x' => $x, 'y' => $y] => $cell;
            }
        }
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
                    $neighbors[] = $target[$i][$j];
                }
            }
        }
        return $neighbors;
    }

    protected function getActiveGrid()
    {
        return $this->grids[$this->current];
    }

    protected function getInactiveGrid()
    {
        return $this->grids[($this->current + 1) % 2];
    }

    public function __toString()
    {
        $out = '';

        foreach ($this->getActiveGrid() as $x => $cols) {
            foreach ($cols as $y => $cell) {
                $out .= $cell;
            }
            $out .= PHP_EOL;
        }

        return $out;
    }
}
