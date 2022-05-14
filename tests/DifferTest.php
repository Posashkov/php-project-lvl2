<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testNormaDiff(): void
    {
        $dirName = 'normalCase';
        $fileName1 = $this->getFileName($dirName, 'file1.json');
        $fileName2 = $this->getFileName($dirName, 'file2.json');

        $results = $this->getFileContent($this->getFileName($dirName, 'results.txt'));
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // yaml test
        $fileName1 = $this->getFileName($dirName, 'file1.yml');
        $fileName2 = $this->getFileName($dirName, 'file2.yml');

        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // swapped results
        $results = $this->getFileContent($this->getFileName($dirName, 'swapped_results.txt'));
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName2, $fileName1));
    }

    public function testFileNotExists(): void
    {
        $dirName = 'normalCase';
        $fileName1 = $this->getFileName($dirName, 'file_not_exists.json');
        $fileName2 = $this->getFileName($dirName, 'file2.json');

        $results = "File {$fileName1} is not exists\n";
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // yaml test
        $fileName1 = $this->getFileName($dirName, 'file_not_exists.yaml');
        $fileName2 = $this->getFileName($dirName, 'file2.yml');

        $results = "File {$fileName1} is not exists\n";
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));
    }

    public function testEmptyFileName(): void
    {
        $dirName = 'normalCase';
        $fileName1 = '';
        $fileName2 = $this->getFileName($dirName, 'file2.json');

        $results = "File name is empty\n";
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // yaml test
        $fileName2 = $this->getFileName($dirName, 'file2.yml');
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));
    }

    public function testEmptyFile(): void
    {
        $dirName =  'emptyFileCase';
        $fileName1 = $this->getFileName($dirName, 'file1.json');
        $fileName2 = $this->getFileName($dirName, 'file2.json');

        $results = $this->getFileContent($this->getFileName($dirName, 'results.txt'));
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // yaml test
        $fileName1 = $this->getFileName($dirName, 'file1.yaml');
        $fileName2 = $this->getFileName($dirName, 'file2.yaml');

        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));
    }

    public function testNestedNormalDIff(): void
    {
        $dirName = 'nestedCase';
        $fileName1 = $this->getFileName($dirName, 'file1.json');
        $fileName2 = $this->getFileName($dirName, 'file2.json');

        $results = $this->getFileContent($this->getFileName($dirName, 'results.txt'));
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // yaml test
        $fileName1 = $this->getFileName($dirName, 'file1.yaml');
        $fileName2 = $this->getFileName($dirName, 'file2.yaml');

        $this->assertEquals($results, \Differ\Differ\genDiff($fileName1, $fileName2));

        // swapped results
        $results = $this->getFileContent($this->getFileName($dirName, 'swapped_results.txt'));
        $this->assertEquals($results, \Differ\Differ\genDiff($fileName2, $fileName1));
    }

    public function getFileName(string $dirName, string $fileName): string
    {
        $pathArray = [__DIR__, 'fixtures', $dirName, $fileName];

        return implode('/', $pathArray);
    }

    public function getFileContent(string $fileName): string
    {
        if (($content = file_get_contents($fileName)) === false) {
            $content = '';
        }

        return $content;
    }
}
