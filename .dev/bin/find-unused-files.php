<?php declare(strict_types=1);

/**
 * Find recursively unused files by looking for unused FQCNs (this file is the underlying script of its corresponding command).
 *
 * File name: find-unused-fqcns.php
 * Created:   2026-06-16 11:28:16
 * @author    Gabriel Tenita <g1704578400@tenita.eu@tenita.eu>
 * @see       https://github.com/the-ge/
 * @copyright Copyright (c) 2026-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

require_once __DIR__.'/../vendor/autoload.php';

use TheGe\DevAid\Utils\UnusedFilesFinder;

echo "Potentially unused files:\n";
foreach (UnusedFilesFinder::runIn(__DIR__.'/../../') as $path) {
    echo $path."\n";
}
