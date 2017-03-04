<?php

declare(strict_types = 1);

namespace Crell\LiPHPe\Test;


use Crell\LiPHPe\World;


class WorldTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate() {
        $w = new World(5, 10);

        $this->assertEquals('E', $w->cellAt(1, 1));
        $this->assertEquals('E', $w->cellAt(5, 10));
    }

    public function testPopulateRocks()
    {
        $w = new World(5, 10);

        $w->place('R', 2, 3)
            ->place('R', 2, 3)
            ->place('R', 1, 1)
            ->place('R', 4, 9);

        $this->assertEquals('R', $w->cellAt(2, 3));
        $this->assertEquals('R', $w->cellAt(1, 1));
        $this->assertEquals('R', $w->cellAt(4, 9));
        $this->assertEquals('E', $w->cellAt(4, 4));
    }

    public function testPopulateOrganisms()
    {
        $w = new World(5, 10);

        $w->place('1', 2, 3)
            ->place('1', 4, 9);

        $this->assertEquals('1', $w->cellAt(2, 3));
        $this->assertEquals('E', $w->cellAt(1, 1));
        $this->assertEquals('1', $w->cellAt(4, 9));
    }

    public function testGetCellNeighbors()
    {
        // Hack the class to make 2 protected methods public for testing.

        $w = new class(3, 3) extends World {
            public function getCellNeighbors(array $target, $x, $y)
            {
                return parent::getCellNeighbors($target, $x, $y);
            }

            public function getActiveGrid()
            {
                return parent::getActiveGrid();
            }
        };

        $this->assertCount(3, $w->getCellNeighbors($w->getActiveGrid(), 0, 0));
        $this->assertCount(5, $w->getCellNeighbors($w->getActiveGrid(), 0, 1));
        $this->assertCount(3, $w->getCellNeighbors($w->getActiveGrid(), 0, 2));
        $this->assertCount(5, $w->getCellNeighbors($w->getActiveGrid(), 1, 0));
        $this->assertCount(8, $w->getCellNeighbors($w->getActiveGrid(), 1, 1));
        $this->assertCount(5, $w->getCellNeighbors($w->getActiveGrid(), 1, 2));
        $this->assertCount(3, $w->getCellNeighbors($w->getActiveGrid(), 2, 0));
        $this->assertCount(5, $w->getCellNeighbors($w->getActiveGrid(), 2, 1));
        $this->assertCount(3, $w->getCellNeighbors($w->getActiveGrid(), 2, 2));
    }

    public function testStep()
    {
        $w = new World(5, 10);

        $w->place('1', 2, 2)
            ->place('1', 2, 3)
            ->place('1', 2, 4);

        $w->step();

        # Two cells should have died
        $this->assertEquals('E', (string)$w->cellAt(2, 2));
        $this->assertEquals('E', (string)$w->cellAt(2, 4));

        # One cell doesn't change
        $this->assertEquals('1', (string)$w->cellAt(2, 3));

        # Two cells should be born
        $this->assertEquals('1', (string)$w->cellAt(1, 3));
        $this->assertEquals('1', (string)$w->cellAt(3, 3));
    }

    public function testStepWithFoodAndRocks()
    {
        $w = new World(5, 10);

        $w->place('1', 2, 2)
            ->place('F', 2, 3)
            ->place('1', 2, 4)
            ->place('R', 3, 3);

        $w->step();

        # Two cells should have died.
        $this->assertEquals('E', (string)$w->cellAt(2, 2));
        $this->assertEquals('E', (string)$w->cellAt(2, 4));

        # Food cell doesn't change.
        $this->assertEquals('F', (string)$w->cellAt(2, 3));

        # Rock cell doesn't change.
        $this->assertEquals('R', (string)$w->cellAt(3, 3));

        # One cell should be born.
        $this->assertEquals('1', (string)$w->cellAt(1, 3));
    }

}
