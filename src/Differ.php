<?php

namespace Differ\Differ;

function genDiff($fileName1, $fileName2, $format = '')
{
//    $format = ($parameters['--format'] ?? '');

    try {
        $firstFileContent = readFile($fileName1, $format);
        $secondFileContent = readFile($fileName2, $format);
    } catch (\Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }

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
    $returnArray = array_map(function ($item) {
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
        throw new \Exception("File name is empty");
    }

    if (!file_exists($fileName)) {
        throw new \Exception("File {$fileName} is not exists");
    }

    $content = file_get_contents($fileName);

//    switch ($format) {
//        case 'json':
              $content = json_decode($content, true);
              if (is_array($content)) {
                  ksort($content);
              } else {
                  $content = [];
              }
//            break;
//        default:
//            break;
//    }

    return $content;
}
