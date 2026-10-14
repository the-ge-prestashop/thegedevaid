<?php declare(strict_types=1);

/**
 * @todo Add description
 *
 * File name: UnusedFilesFinder.php
 * Created:   2026-10-14 12:32:26
 * @author    Gabriel Tenita <g1704578400@tenita.eu>
 * @see       https://github.com/the-ge/
 * @copyright Copyright (c) 2026-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\DevAid\Utils;


class UnusedFilesFinder
{
    /** @return array<string, string> */
    public static function runIn(string $projectPath): array
    {
        // 1. Find all FQCNs in the provided project path 'src/ directory'
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator("{$projectPath}src"));
        $classes  = [];
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $fqcn = self::getFqcn($file);

                if ($fqcn === null) {
                    continue;
                }

                $classes[$fqcn] = substr($file->getPathname(), \strlen($projectPath));
            }
        }

        // 2. Scan project for references
        $allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($projectPath));
        $unused   = $classes;
        foreach ($allFiles as $file) {
            $path = $file->getPathname();

            if ($path === false) {
                continue;
            }

            if ($file->getExtension() === 'php' && !str_contains($path, '.dev/shelf')) {
                $content = file_get_contents($path);

                if ($content === false) {
                    continue;
                }

                foreach (array_keys($classes) as $fqcn) {
                    if (isset($unused[$fqcn]) && str_contains($content, $fqcn)) {
                        unset($unused[$fqcn]);
                    }
                }
            }
        }

        return $unused;
    }

    private static function getFqcn(\SplFileInfo $file): ?string
    {
        $content = file_get_contents($file->getPathname());

        if ($content === false) {
            return null;
        }

        if (preg_match('/namespace (.*?);.*class (\w+)/s', $content, $matches) === 1) {
            return $matches[1].'\\'.$matches[2];
        }

        return null;
    }
}
