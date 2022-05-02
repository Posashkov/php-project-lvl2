<?php

namespace Hexlet\code;

use Docopt;

function gendiff()
{
    $doc = <<<DOC
Generate diff

Usage:
 gendiff (-h|--help)
 gendiff (-v|--version)

Options:
 -h --help      Show this screen
 -v --verison   Show version
DOC;

    $returnStr = [];

    $args = Docopt::handle($doc);
    foreach ($args as $k => $v) {
        $returnStr[] = $k . ': ' . json_encode($v) . PHP_EOL;
    }

    return implode('', $returnStr);
}
