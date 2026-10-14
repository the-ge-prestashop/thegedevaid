<?php declare(strict_types=1);

/**
 * Measures time/memory cost of building a HTML document (this file is the underlying script of its corresponding command).
 *
 * File name: performance-test.php
 * Created:   2026-06-16 11:29:07
 * @author    Gabriel Tenita <g1704578400@tenita.eu@tenita.eu>
 * @see       https://github.com/the-ge/
 * @copyright Copyright (c) 2026-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

require_once __DIR__.'/../vendor/autoload.php';

use Widgettery\Dev\Tool\Performance\PerformanceMeter;
use Widgettery\Setup\Config;

$html        = '';
$iterations  = 1000;
$totalTime   = 0;
$totalMemory = 0;
$totalSize   = 0;

for ($i = 0; $i < $iterations; $i++) {
    [$memory, $time, $size] = new PerformanceMeter()->run(
        $i,
        Config::from(
            [
                'title'       => 'Widgettery: Performance Test #',
                'subtitle'    => 'Composite widgets build performance test #',
                'description' => 'Performance test for building a page containing most of the available HTML elements. This is test #',
                'route'       => basename(__FILE__, '.php'),
                'lang'        => ['iso' => 'en-US'],
            ],
            __DIR__.'/../config/site.php',
            __DIR__.'/../config/page.php',
        )
    );
    $totalMemory += $memory;
    $totalTime += $time;
    $totalSize += $size;
}

$avgTime   = $totalTime / $iterations;
$avgMemory = $totalMemory / $iterations;
$avgSize   = $totalSize / $iterations;

echo "Iterations: {$iterations}\n";
echo 'Total Time: '.number_format($totalTime / 1000, 4)."s\n";
echo 'Avg Time per iteration: '.number_format($avgTime, 4)."ms\n";
echo 'Avg Memory Increase: '.number_format($avgMemory, 4)."MB\n";
echo 'Average HTML Size: '.number_format($avgSize, 2)."KB\n";
