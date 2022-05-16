<?php

namespace Differ\Differ;

use function Differ\Parsers\ReadFile;
use function Differ\Formatters\formatString;
use function Differ\BuildAst\buildAst;
use function Differ\BuildAst\isNode;
use function Differ\BuildAst\getNodeValue;
use function Differ\BuildAst\setNodeStatusRemoved;
use function Differ\BuildAst\setNodeStatusEqual;
use function Differ\BuildAst\setNodeStatusChanged;
use function Differ\BuildAst\setNodeStatusAdded;
use function Differ\BuildAst\setNodeNewValue;
use function Differ\BuildAst\getListChildren;
use function Differ\BuildAst\setListChildren;
use function Functional\sort;

function genDiff(string $fileName1, string $fileName2, string $formatName = ''): string
{
    try {
        $firstFileData = buildAst(ReadFile($fileName1));
        $secondFileData = buildAst(ReadFile($fileName2));
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }

    $returnArray = getDifferenceBetweenContent($firstFileData, $secondFileData);

    $resultStr = formatString($returnArray, $formatName);

    return $resultStr;
}

/**
 * @param array<mixed> $firstArray
 * @param array<mixed> $secondArray
 * @return array<mixed>
 */
function getDifferenceBetweenContent(array $firstArray, array $secondArray): array
{
    $allKeys = array_unique([...array_keys($firstArray), ...array_keys($secondArray)]);
    $allKeys = sort($allKeys, fn ($left, $right) => strcmp($left, $right));

    $returnArray = array_map(function ($key) use ($firstArray, $secondArray) {
        $firstItem = $firstArray[$key] ?? null;
        $secondItem = $secondArray[$key] ?? null;

        if (is_null($firstItem)) {
            return setNodeStatusAdded($secondItem);
        }

        if (is_null($secondItem)) {
            return setNodeStatusRemoved($firstItem);
        }

        if (!isNode($firstItem) && !isNode($secondItem)) {
            $firstItem = setNodeStatusEqual($firstItem);
            $firstItem = setListChildren(
                $firstItem,
                getDifferenceBetweenContent(
                    getListChildren($firstItem),
                    getListChildren($secondItem)
                )
            );
            return $firstItem;
        }

        if (getNodeValue($firstItem) === getNodeValue($secondItem)) {
            return setNodeStatusEqual($firstItem);
        }

        if (getNodeValue($firstItem) !== getNodeValue($secondItem)) {
            $firstItem = setNodeStatusChanged($firstItem);
            $firstItem = setNodeNewValue($firstItem, getNodeValue($secondItem));
            return $firstItem;
        }
    }, $allKeys);

    return $returnArray;
}
