<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\applyPlainFormatter;
use function Differ\Formatters\Stylish\applyStylishFormatter;

/**
 * @param array<mixed> $valuesArray
 */
function formatString(array $valuesArray, string $formatName): string
{
    switch ($formatName) {
        case 'plain':
            $formattedString = applyPlainFormatter($valuesArray);
            break;
        case 'stylish':
        default:
            $formattedString = applyStylishFormatter($valuesArray);
            break;
    }

    return $formattedString;
}
