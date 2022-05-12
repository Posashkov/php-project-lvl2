<?php

namespace Differ\Formater;

/**
 * @param array<mixed> $valuesArray
 */
function FormatString(array $valuesArray, string $formater): string
{
    switch ($formater) {
        case 'stylish':
        default:
            $formattedString = applyStylishFormater($valuesArray);
            break;
    }

    return $formattedString;
}

/**
 * @param array<mixed> $valuesArray
 */
function applyStylishFormater(array $valuesArray, int $depth = 0): string
{
    $returnArray = array_map(function ($item) use ($depth) {
        $type = $item['type'];

        if ($type == 'node') {
            $value = $item['value'];
        } else {
            $value = applyStylishFormater($item['children'], $depth + 1);
        }

        $name = $item['name'];
        $status = $item['status'] ?? 'equal';

        switch ($status) {
            case 'added':
                $status = '+';
                break;
            case 'removed':
                $status = '-';
                break;
            case 'equal':
            default:
                $status = ' ';
                break;
        }

        if (is_bool($value)) {
            $value = ($value === true) ? 'true' : 'false';
        }

        if (is_null($value)) {
            $value = 'null';
        }

        $spacer = str_repeat(' ', $depth * 4);
        $returnStr = "{$spacer}  {$status} {$name}: {$value}";

        if ($type == 'node') {
            $returnStr .= "\n";
        }

        return $returnStr;
    }, $valuesArray);

    $spacer = str_repeat(' ', $depth * 4);

    return implode('', array_merge(["{\n"], $returnArray, ["{$spacer}}\n"]));
}
