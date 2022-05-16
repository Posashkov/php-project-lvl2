<?php

namespace Differ\Formatters\Plain;

use function Differ\BuildAst\isNode;
use function Differ\BuildAst\getNodeValue;
use function Differ\BuildAst\getNodeNewValue;
use function Differ\BuildAst\getListChildren;
use function Differ\BuildAst\getNodeName;
use function Differ\BuildAst\getNodeStatus;

/**
 * @param array<mixed> $valuesArray
 */
function applyPlainFormatter(array $valuesArray): string
{
    return rtrim(buildArrayForPlain($valuesArray), "\n");
}

/**
 * @param array<mixed> $valuesArray
 */
function buildArrayForPlain(array $valuesArray, string $parentName = ''): string
{
    $returnArray = array_map(function ($item) use ($parentName) {
        if ($parentName != '') {
            $name = implode('.', [$parentName, getNodeName($item)]);
        } else {
            $name = getNodeName($item);
        }

        $value = prepareValue(getNodeValue($item));
        switch (getNodeStatus($item)) {
            case 'added':
                $returnStr = "Property '{$name}' was added with value: {$value}\n";
                break;
            case 'removed':
                $returnStr = "Property '{$name}' was removed\n";
                break;
            case 'changed':
                $newValue = prepareValue(getNodeNewValue($item));
                $returnStr = "Property '{$name}' was updated. From {$value} to {$newValue}\n";
                break;
            case 'equal':
            default:
                $returnStr = (isNode($item)) ? '' : buildArrayForPlain(getListChildren($item), $name);
                break;
        }

        return $returnStr;
    }, $valuesArray);

    return implode('', $returnArray);
}

/**
 * @param bool|null|string|array<mixed> $value
 * @return string $value
 */
function prepareValue($value)
{
    if (is_string($value)) {
        $value = "'{$value}'";
    }

    if (is_bool($value)) {
        $value = ($value === true) ? 'true' : 'false';
    }

    if (is_null($value)) {
        $value = 'null';
    }

    if (is_array($value)) {
        $value = '[complex value]';
    }

    return $value;
}
