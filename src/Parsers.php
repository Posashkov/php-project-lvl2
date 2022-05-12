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
        $content = '';
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
        case 'json':
            $parsedContent = json_decode($content, false);
            break;
        case 'yml':
        case 'yaml':
            $parsedContent = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            $parsedContent = (object)[];
            break;
    }

    if (empty($parsedContent)) {
        $parsedContent = (object)[];
    }

    return $parsedContent;
}
