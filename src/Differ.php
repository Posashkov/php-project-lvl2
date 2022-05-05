<?php

namespace Differ\Differ;

use function Differ\Parsers\ReadFile;

function genDiff(string $fileName1, string $fileName2): string
{
    try {
        $firstFileContent = ReadFile($fileName1);
        $secondFileContent = ReadFile($fileName2);
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }

    $allKeys = array_keys(array_merge($firstFileContent, $secondFileContent));

    $returnArray = array_reduce($allKeys, function ($acc, $key) use ($firstFileContent, $secondFileContent) {
        $keyExistsInFirst = array_key_exists($key, $firstFileContent);
        $keyExistsInSecond = array_key_exists($key, $secondFileContent);

        if ($keyExistsInFirst && $keyExistsInSecond) {
            if ($firstFileContent[$key] === $secondFileContent[$key]) {
                $acc[] = [' ', $key, $firstFileContent[$key]];
            } else {
                $acc[] = ['-', $key, $firstFileContent[$key]];
                $acc[] = ['+', $key, $secondFileContent[$key]];
            }
        } else {
            if ($keyExistsInFirst) {
                $acc[] = ['-', $key, $firstFileContent[$key]];
            }
            if ($keyExistsInSecond) {
                $acc[] = ['+', $key, $secondFileContent[$key]];
            }
        }

        return $acc;
    }, []);

    $resultStr = getFormattedStringWithDiff($returnArray);

    return $resultStr;
}

/**
 * @param array<mixed> $valuesArray
 */
function getFormattedStringWithDiff(array $valuesArray): string
{
    $returnArray = array_map(function ($item) {
        [$status, $key, $value] = $item;

        if (is_bool($value)) {
            $value = ($value === true) ? 'true' : 'false';
        }

        return "  {$status} {$key}: {$value}\n";
    }, $valuesArray);

    return implode('', ["{\n", ...$returnArray, "}\n"]);
}
