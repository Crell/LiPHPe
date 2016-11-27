<?php

declare(strict_types = 1);

namespace Crell\LiPHPe\Test;

use Crell\LiPHPe\Cell;

class CellTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     *
     *
     * @param $start
     *   The initial state of a Cell (or rather its mirror).
     * @param array $neighbors
     *   An array of a cell's neighbors.
     * @param $expected
     *   The expected state after a single generation.
     *
     * @dataProvider updateValueProvider
     */
    public function test_updateValue($start, array $neighbors, $expected)
    {
        # The state of the local cell doesn't matter, it's the mirror cell that matters.
        $c = new Cell($start, new Cell($start));

        $c->setSourceNeighbors($neighbors);
        $c->updateValue();

        $this->assertEquals($expected, $c->getState());
    }

    public function updateValueProvider()
    {
        # Empty, 1 neighbor
        yield ['E', [new Cell('1')], 'E'];
        yield ['E', [new Cell('E')], 'E'];
        // Empty, 2 neighbors
        yield ['E', [new Cell('E'), new Cell('1')], 'E'];
        yield ['E', [new Cell('1'), new Cell('1')], 'E'];
        // Empty, 3 neighbors
        yield ['E', [new Cell('1'), new Cell('1'), new Cell('1')], '1'];
        yield ['E', [new Cell('1'), new Cell('1'), new Cell('F')], '1'];
        // Empty, 4 neighbors
        yield ['E', [new Cell('1'), new Cell('1'), new Cell('1'), new Cell('E')], '1']; // Born from 3 neighbors
        yield ['E', [new Cell('1'), new Cell('2'), new Cell('1'), new Cell('R')], 'E']; // Hostile neighbor prevents birth
        // Living, 1 neighbor
        yield ['1', [new Cell('1')], 'E'];
        // Living, 2 neighbors
        yield ['1', [new Cell('1'), new Cell('1')], '1'];
        yield ['1', [new Cell('1'), new Cell('F')], '1'];
        // Living, 3 neighbors
        yield ['1', [new Cell('1'), new Cell('1'), new Cell('1')], '1'];
        yield ['1', [new Cell('1'), new Cell('1'), new Cell('F')], '1'];
        yield ['1', [new Cell('1'), new Cell('1'), new Cell('2')], '1']; // Still 2 friendly neighbors
        // Living, 4 neighbors
        yield ['1', [new Cell('1'), new Cell('1'), new Cell('1'), new Cell('1')], 'E']; # Die from over-population
        yield ['1', [new Cell('1'), new Cell('1'), new Cell('1'), new Cell('F')], '1']; # Food doesn't cause over-population
        // Rocks should always stay a rock
        yield ['R', [], 'R'];
        yield ['R', [new Cell('1'), new Cell('1'), new Cell('1'), new Cell('R')], 'R'];
        // Food should always stay food
        yield ['F', [], 'F'];
        yield ['F', [new Cell('1'), new Cell('1'), new Cell('1'), new Cell('R')], 'F'];
    }
}
