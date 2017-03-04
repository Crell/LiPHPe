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

}
