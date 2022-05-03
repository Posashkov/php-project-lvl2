<?php

namespace genDIffer\Cli;

use Docopt;

function start()
{
    $doc = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help          Show this screen
  -v --verison       Show version
  --format <fmt>     Report format [default: stylish]
DOC;

    return Docopt::handle($doc);
}
