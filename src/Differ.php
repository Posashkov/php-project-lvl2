<?php

namespace Differ\Differ;

use function Differ\Parsers\ReadFile;
use function Differ\Formater\FormatString;

function genDiff(string $fileName1, string $fileName2, string $formater = ''): string
{
    try {
        $firstFileData = getAst(ReadFile($fileName1));
        $secondFileData = getAst(ReadFile($fileName2));
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }

    $returnArray = getDifferenceBetweenContent($firstFileData, $secondFileData);

    $resultStr = FormatString($returnArray, $formater);

    return $resultStr;
}

/**
 * @return array<mixed>
 */
function getAst(object $objectTree): array
{
    $returnItems = [];
    foreach ((array)$objectTree as $key => $node) {
        if (!is_object($node)) {
            $returnItems[$key] = ['type' => 'node', 'name' => $key, 'value' => $node];
        } else {
            $returnItems[$key] =  ['type' => 'list', 'name' => $key, 'children' => getAst($node)];
        }
    }

    return $returnItems;
}

/**
 * @param array<mixed> $firstArray
 * @param array<mixed> $secondArray
 * @return array<mixed>
 */
function getDifferenceBetweenContent(array $firstArray, array $secondArray): array
{
    $allKeys = array_keys(array_merge($firstArray, $secondArray));

    sort($allKeys);

    $returnArray = array_reduce($allKeys, function ($acc, $key) use ($firstArray, $secondArray) {

        $isFirstKeyExists = array_key_exists($key, $firstArray);
        $isSecondKeyExists = array_key_exists($key, $secondArray);

        if ($isFirstKeyExists && $isSecondKeyExists) {
            $isFirstIsNode = ($firstArray[$key]['type'] == 'node');
            $isSecondIsNode = ($secondArray[$key]['type'] == 'node');

            if ($isFirstIsNode && $isSecondIsNode) {
                if ($firstArray[$key] == $secondArray[$key]) {
                    $firstArray[$key]['status'] = 'equal';
                    $acc[] = $firstArray[$key];
                } else {
                    $firstArray[$key]['status'] = 'removed';
                    $acc[] = $firstArray[$key];

                    $secondArray[$key]['status'] = 'added';
                    $acc[] = $secondArray[$key];
                }
            }

            if (!$isFirstIsNode && !$isSecondIsNode) {
                $firstArray[$key]['status'] = 'equal';
                $firstArray[$key]['children'] =
                    getDifferenceBetweenContent($firstArray[$key]['children'], $secondArray[$key]['children']);
                $acc[] = $firstArray[$key];
            }

            if ($isFirstIsNode !== $isSecondIsNode) {
                $firstArray[$key]['status'] = 'removed';
                $acc[] = $firstArray[$key];

                $secondArray[$key]['status'] = 'added';
                $acc[] = $secondArray[$key];
            }
        } else {
            if ($isFirstKeyExists) {
                $firstArray[$key]['status'] = 'removed';
                $acc[] = $firstArray[$key];
            }

            if ($isSecondKeyExists) {
                $secondArray[$key]['status'] = 'added';
                $acc[] = $secondArray[$key];
            }
        }

        return $acc;
    }, []);

    return $returnArray;
}
