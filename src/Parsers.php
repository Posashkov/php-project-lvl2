<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @return Object
 */
function ReadFile(string $fileName = ''): object
{
    if ($fileName == '') {
        throw new \Exception("File name is empty");
    }

    if (!file_exists($fileName)) {
        throw new \Exception("File {$fileName} is not exists");
    }

    if (($content = file_get_contents($fileName)) === false) {
        return (object)[];
    }

    $format = pathinfo($fileName, PATHINFO_EXTENSION);

    $parsedContent = ParseContent($content, $format);

    return $parsedContent;
}

/**
 * @return Object
 */
function ParseContent(string $content, string $format = 'json'): object
{
    switch ($format) {
        case 'yml':
        case 'yaml':
            $parsedContent = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        case 'json':
        default:
            $parsedContent = json_decode($content, false);
            break;
    }

    if (is_null($parsedContent) || $parsedContent == "") {
        return (object)[];
    }

    return $parsedContent;
}
