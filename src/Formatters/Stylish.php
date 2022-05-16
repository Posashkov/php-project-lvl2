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
                $returnString = makeReturnString($name, $value, '+', $depth, isNode($item));
                break;
            case 'removed':
                $returnString = makeReturnString($name, $value, '-', $depth, isNode($item));
                break;
            case 'changed':
                $isNotComplexValue = (!is_array(getNodeNewValue($item)));

                if (!is_array(getNodeNewValue($item))) {
                    $newValue = prepareValue(getNodeNewValue($item));
                } else {
                    $newValue = buildArrayForStylish(getNodeNewValue($item), $depth + 1);
                }

                $returnString =
                    makeReturnString($name, $value, '-', $depth, isNode($item)) .
                    makeReturnString($name, $newValue, '+', $depth, $isNotComplexValue);
                break;
            case 'equal':
            default:
                $returnString = makeReturnString($name, $value, ' ', $depth, isNode($item));
                break;
        }

        return $returnString;
    }, $valuesArray);

    return implode('', array_merge(["{\n"], $returnArray, [spaceRepeater($depth) . "}\n"]));
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

function spaceRepeater(int $depth = 0): string
{
    return str_repeat(' ', $depth * 4);
}

/**
 * @param string|numeric $value
 */
function makeReturnString(string $name, $value, string $status, int $depth, bool $addNewLine): string
{
    $newLine = ($addNewLine) ? "\n" : "";

    return spaceRepeater($depth) . "  {$status} {$name}: {$value}{$newLine}";
}
