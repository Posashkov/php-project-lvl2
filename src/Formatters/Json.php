<?php

namespace Differ\Formatters\Json;

use function Differ\BuildAst\isNode;
use function Differ\BuildAst\getNodeValue;
use function Differ\BuildAst\getNodeNewValue;
use function Differ\BuildAst\getListChildren;
use function Differ\BuildAst\getNodeName;
use function Differ\BuildAst\getNodeStatus;

/**
 * @param array<mixed> $valuesArray
 */
function applyJsonFormatter(array $valuesArray): string
{
    $returnStr = json_encode(buildArrayForJson($valuesArray));
    return ($returnStr !== false) ? $returnStr : '';
}

/**
 * @param array<mixed> $valuesArray
 * @return array<mixed>
 */
function buildArrayForJson(array $valuesArray)
{
    $returnArray = array_map(function ($item) {
        if (isNode($item)) {
            $value = getNodeValue($item);
        } else {
            $value = buildArrayForJson(getListChildren($item));
        }

        $returnStr = ["name" => getNodeName($item), "status" => getNodeStatus($item), "value" => $value];

        switch (getNodeStatus($item)) {
            case 'changed':
                if (!is_array(getNodeNewValue($item))) {
                    $newValue = getNodeNewValue($item);
                } else {
                    $newValue = buildArrayForJson(getNodeNewValue($item));
                }
                $returnStr["new_value"] = $newValue;
                break;
            case 'added':
            case 'removed':
                break;
            case 'equal':
            default:
                $returnStr["status"] = 'unchanged';
                break;
        }

        return $returnStr;
    }, $valuesArray);

    return array_values($returnArray);
}
