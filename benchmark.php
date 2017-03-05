<?php

declare(strict_types = 1);

use Crell\LiPHPe\World;

require_once __DIR__ . '/src/Cell.php';
require_once __DIR__ . '/src/World.php';

$start = $end = 0;

const SIZE = 50;
const GENERATIONS = 100;
const TESTS = 5;

function makeWorld()
{
    $w = new World(SIZE, SIZE);

    $w->place('1', 2, 2)
        ->place('1', 2, 3)
        ->place('1', 2, 4);

    return $w;
}

function test() {
    $w = makeWorld();

    $start = microtime(true);
    for ($i = 0; $i < GENERATIONS; ++$i) {
        $w->step();
    }
    $stop = microtime(true);

    return $stop - $start;
}

$results = [];
for ($i = 0; $i < TESTS; ++$i) {
    $results[] = test();
}

$avg = number_format(array_sum($results) / TESTS, 2);

$out = <<<END
Size: %d
Generations: %d
Tests: %d
Max time: %f
Min time: %f
Avg time: %f

END;

printf($out, SIZE, GENERATIONS, TESTS, max($results), min($results), $avg);
