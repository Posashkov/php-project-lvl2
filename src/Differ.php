<?php

namespace Differ\Differ;

function genDiff($parameters = [])
{
    $fileName1 = ($parameters['<firstFile>'] ?? '');
    $fileName2 = ($parameters['<secondFile>'] ?? '');
    $format = ($parameters['--format'] ?? '');

    $firstFileContent = readFile($fileName1, $format);
    $secondFileContent = readFile($fileName2, $format);

    $allKeys = array_keys(array_merge($firstFileContent, $secondFileContent));

    $returnArray = array_reduce($allKeys, function ($acc, $key) use ($firstFileContent, $secondFileContent) {
        $keyExistsInFirst = array_key_exists($key, $firstFileContent);
        $keyExistsInSecond = array_key_exists($key, $secondFileContent);

        if ($keyExistsInFirst && $keyExistsInSecond) {
            if ($firstFileContent[$key] === $secondFileContent[$key]) {
                $acc[] = [' ', $key, $firstFileContent[$key]];
            } else {
                $acc[] = ['-', $key, $firstFileContent[$key]];
                $acc[] = ['+', $key, $secondFileContent[$key]];
            }
        } else {
            if ($keyExistsInFirst) {
                $acc[] = ['-', $key, $firstFileContent[$key]];
            }
            if ($keyExistsInSecond) {
                $acc[] = ['+', $key, $secondFileContent[$key]];
            }
        }

        return $acc;
    }, []);

    $resultStr = getFormattedStringWithDiff($returnArray, $format);

    return $resultStr;
}

function getFormattedStringWithDiff($valuesArray, $format)
{
    $returnArray = array_map(function($item) {
        [$status, $key, $value] = $item;

        if (is_bool($value)) {
            $value = ($value === true) ? 'true' : 'false';
        }

        return "  {$status} {$key}: {$value}\n";
    }, $valuesArray);

//    switch ($format) {
//        case 'json':
            $returnStr = implode('', ["{\n", ...$returnArray, "}\n"]);
//            break;
//        default:
//            $returnStr = impode('', $returnArray);
//            break;
//    }

    return $returnStr;
}

function readFile($fileName = '', $format = 'json')
{
    if ($fileName == '') {
        return '';
    }

    $content = file_get_contents($fileName);

//    switch ($format) {
//        case 'json':
            $content = json_decode($content, true);
            ksort($content);
//            break;
//        default:
//            break;
//    }

    return $content;
}

