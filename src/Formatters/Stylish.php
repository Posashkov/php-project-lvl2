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
function applyStylishFormatter(array $valuesArray, int $depth = 0): string
{
    $returnArray = array_map(function ($item) use ($depth) {
        if (isNode($item)) {
            $value = prepareValue(getNodeValue($item));
        } else {
            $value = applyStylishFormatter(getListChildren($item), $depth + 1);
        }

        $name = getNodeName($item);
        $status = '';
        $statusNewValue = '';

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

        $returnStr = repeater($depth) . "  {$status} {$name}: {$value}";
        if (isNode($item)) {
            $returnStr .= "\n";
        }
        if ($statusNewValue !== '') {
            if (!is_array(getNodeNewValue($item))) {
                $newValue = prepareValue(getNodeNewValue($item));
                $newValue .= "\n";
            } else {
                $newValue = applyStylishFormatter(getNodeNewValue($item), $depth + 1);
            }
            $returnStr .= repeater($depth) . "  {$statusNewValue} {$name}: {$newValue}";
        }

        return $returnStr;
    }, $valuesArray);

    return implode('', array_merge(["{\n"], $returnArray, [repeater($depth) . "}\n"]));
}

/**
 * @param bool|null|string|array<mixed> $value
 * @return string $value
 */
function prepareValue($value)
{
    if (is_bool($value)) {
        $value = ($value === true) ? 'true' : 'false';
    }

    if (is_null($value)) {
        $value = 'null';
    }

    if (is_array($value)) {
        $value = '[' . implode(', ', $value) . ']';
    }

    return $value;
}

function repeater(int $depth = 0): string
{
    return str_repeat(' ', $depth * 4);
}
