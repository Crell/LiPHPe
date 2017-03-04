<?php

declare(strict_types = 1);

namespace Crell\LiPHPe\Test;


use Crell\LiPHPe\World;


class WorldTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate() {
        $w = new World(5, 10);

        $this->assertEquals('E', $w->cellAt(1, 1));
        $this->assertEquals('E', $w->cellAt(4, 4));
    }

}
