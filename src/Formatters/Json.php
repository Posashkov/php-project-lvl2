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
        $value = (isNode($item)) ? getNodeValue($item) : buildArrayForJson(getListChildren($item));

        switch (getNodeStatus($item)) {
            case 'changed':
                $newValue = (!is_array(getNodeNewValue($item))) ?
                    getNodeNewValue($item) :
                    buildArrayForJson(getNodeNewValue($item));
                $returnStr = [
                    "name" => getNodeName($item), "status" => getNodeStatus($item),
                    "value" => $value, "new_value" => $newValue
                ];
                break;
            case 'added':
            case 'removed':
                $returnStr = ["name" => getNodeName($item), "status" => getNodeStatus($item), "value" => $value];
                break;
            case 'equal':
            default:
                $returnStr = ["name" => getNodeName($item), "status" => 'unchanged', "value" => $value];
                break;
        }

        return $returnStr;
    }, $valuesArray);

    return array_values($returnArray);
}
