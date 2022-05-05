<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @return array<mixed>
 */
function ReadFile(string $fileName = ''): array
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
 * @return array<mixed>
 */
function ParseContent(string $content, string $format = 'json'): array
{
    switch ($format) {
        case 'json':
            $parsedContent = json_decode($content, true);
            break;
        case 'yml':
        case 'yaml':
            $parsedContent = Yaml::parse($content);
            break;
        default:
            $parsedContent = [];
            break;
    }

    if (!is_array($parsedContent)) {
        $parsedContent = [];
    }

    ksort($parsedContent);

    return $parsedContent;
}
