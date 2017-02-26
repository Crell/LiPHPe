<?php

namespace Crell\LiPHPe;


class Cell
{

    /**
     * The current state of this cell.
     *
     * @var string
     */
    protected $state;

    /**
     * The neighbor cells (techncially in the other state buffer) that influence this cell.
     *
     * @var Cell[]
     */
    protected $neighbors = [];

    /**
     * This cell's counterpart in the other state buffer.
     *
     * @var Cell
     */
    protected $mirror;

    public function __construct($start, Cell $mirror = null)
    {
        $this->state = $start;
        $this->mirror = $mirror;
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
        // The current state is actually the state of the mirror cell, since that determines
        // whether we may die or may be born.

        $currentState = $this->mirror->getState();

        // Rocks and Food never change.
        if (in_array($currentState, ['R', 'F'], true)) {
            return $this;
        }

        // Precompute the neighborStates for performance.
        $neighborCounts = array_fill_keys(['F', 'R', 'E', $currentState], 0);
        foreach ($this->neighbors as $neighbor) {
            $neighborString = (string)$neighbor;
            $neighborCounts[$neighborString] = isset($neighborCounts[$neighborString]) ? $neighborCounts[$neighborString] + 1 : 1;
        }

        $liveNeighbors = count(array_filter($this->neighbors, function(Cell $cell) {
            return $cell->isPlayer();
        }));

        // See if a cell should be born.
        if ($currentState === 'E' && in_array($liveNeighbors, range(1, 4)) && $liveNeighbors + $neighborCounts['F'] >=3) {
            $speciesCounts = $this->arrayFilterKey($neighborCounts, 'is_numeric');
            $candidateState = array_keys($speciesCounts, max($speciesCounts))[0];
            if ($speciesCounts[$candidateState] + $neighborCounts['F'] >= 3) {
                $this->state = $candidateState;
            }
        // Otherwise, see if it dies.
        } elseif (is_numeric($currentState) and ($liveNeighbors >= 4 or ($neighborCounts[$currentState] + $neighborCounts['F']) < 2)) {
            $this->state = 'E';
        } else {
            $this->state = $currentState;
        }

        return $this;
    }

    public function setMirrorCell(Cell $mirror)
    {
        $this->mirror = $mirror;
        return $this;
    }

    /**
     * Filtering a array by its keys using a callback.
     *
     * @param array $array
     *   The array to filter.
     * @param callable $callback
     *   The filter callback, that will get the key as first argument.
     *
     * @return array The remaining key => value combinations from $array.
     */
    protected function arrayFilterKey(array $array, $callback)
    {
        $matchedKeys = array_filter(array_keys($array), $callback);
        return array_intersect_key($array, array_flip($matchedKeys));
    }

    public function isPlayer()
    {
        return is_numeric($this->state);
    }

    public function __toString()
    {
        return $this->state;
    }
}
