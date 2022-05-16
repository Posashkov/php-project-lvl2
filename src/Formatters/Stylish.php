<?php

namespace Differ\Formatters\Stylish;

use function Differ\BuildAst\isNode;
use function Differ\BuildAst\getNodeValue;
use function Differ\BuildAst\getNodeNewValue;
use function Differ\BuildAst\getListChildren;
use function Differ\BuildAst\getNodeName;
use function Differ\BuildAst\getNodeStatus;

/**
 * @param array<mixed> $valuesArray
 */
function applyStylishFormatter(array $valuesArray): string
{
    return rtrim(buildArrayForStylish($valuesArray), "\n");
}

/**
 * @param array<mixed> $valuesArray
 */
function buildArrayForStylish(array $valuesArray, int $depth = 0): string
{
    $returnArray = array_map(function ($item) use ($depth) {
        $name = getNodeName($item);
        $value = (isNode($item)) ?
            prepareValue(getNodeValue($item)) :
            buildArrayForStylish(getListChildren($item), $depth + 1);

        switch (getNodeStatus($item)) {
            case 'added':
                $status = '+';
                break;
            case 'removed':
                $status = '-';
                break;
            case 'changed':
                $status = '-';
                $statusNewValue = '+';
                break;
            case 'equal':
            default:
                $status = ' ';
                break;
        }

        $returnStr[] = repeater($depth) . "  {$status} {$name}: {$value}";
        if (isNode($item)) {
            $returnStr[] = "\n";
        }
        if (isset($statusNewValue) && $statusNewValue !== '') {
            $returnStr[] = repeater($depth) . "  {$statusNewValue} {$name}: ";
            if (!is_array(getNodeNewValue($item))) {
                $returnStr[] = prepareValue(getNodeNewValue($item));
                $returnStr[] = "\n";
            } else {
                $returnStr[] = buildArrayForStylish(getNodeNewValue($item), $depth + 1);
            }
        }

        return implode('', $returnStr);
    }, $valuesArray);

    return implode('', array_merge(["{\n"], $returnArray, [repeater($depth) . "}\n"]));
}

/**
 * @param bool|null|string|array<mixed>|numeric $value
 * @return string|numeric $value
 */
function prepareValue($value)
{
    if (is_bool($value)) {
        return ($value === true) ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_array($value)) {
        return '[' . implode(', ', $value) . ']';
    }

    return $value;
}

function repeater(int $depth = 0): string
{
    return str_repeat(' ', $depth * 4);
}
