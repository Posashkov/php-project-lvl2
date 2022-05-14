<?php

namespace Differ\Differ;

use function Differ\Parsers\readFile;
use function Differ\Formater\formatString;
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

function genDiff(string $fileName1, string $fileName2, string $formater = ''): string
{
    try {
        $firstFileData = buildAst(readFile($fileName1));
        $secondFileData = buildAst(readFile($fileName2));
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }

    $returnArray = getDifferenceBetweenContent($firstFileData, $secondFileData);

    $resultStr = formatString($returnArray, $formater);

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

    sort($allKeys);

    $returnArray = array_map(function ($key) use ($firstArray, $secondArray) {
        $firstItem = $firstArray[$key] ?? null;
        $secondItem = $secondArray[$key] ?? null;

        if (!$firstItem) {
            setNodeStatusAdded($secondItem);
            return $secondItem;
        }

        if (!$secondItem) {
            setNodeStatusRemoved($firstItem);
            return $firstItem;
        }

        if (!isNode($firstItem) && !isNode($secondItem)) {
            setNodeStatusEqual($firstItem);
            setListChildren(
                $firstItem,
                getDifferenceBetweenContent(
                    getListChildren($firstItem),
                    getListChildren($secondItem)
                )
            );
            return $firstItem;
        }

        if (getNodeValue($firstItem) == getNodeValue($secondItem)) {
            setNodeStatusEqual($firstItem);
            return $firstItem;
        }

        if (getNodeValue($firstItem) != getNodeValue($secondItem)) {
            setNodeStatusChanged($firstItem);
            setNodeNewValue($firstItem, getNodeValue($secondItem));
            return $firstItem;
        }
    }, $allKeys);

    return $returnArray;
}
