<?php

namespace App\Tests\Service\GenerateFile;

use App\Service\GenerateFile\GenerateTxt;
use PHPUnit\Framework\TestCase;

class GenerateTxtTest extends TestCase
{
    private $filename;

    public function setUp()
    {
        $generateCsvService = new GenerateTxt();
        $generateCsvService->generate('Test data', 'testTxtFile');
        $this->filename = 'testTxtFile' . GenerateTxt::FILE_EXTENSION;
    }

    public function tearDown()
    {
        unlink($this->filename);
    }

    public function test_should_create_new_txt_file()
    {
        self::assertTrue(file_exists($this->filename));
    }

    public function test_should_return_proper_content()
    {
        self::assertSame('Test data', file_get_contents($this->filename));
    }
}