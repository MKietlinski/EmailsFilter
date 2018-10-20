<?php

namespace App\Tests\Service\GenerateFile;

use App\Service\GenerateFile\GenerateCsv;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PHPUnit\Framework\TestCase;

class GenerateCsvTest extends TestCase
{
    private $filename;

    public function setUp()
    {
        $generateCsvService = new GenerateCsv();
        $generateCsvService->generate(['email@email.com', 'john@email.com'], 'testCsvFile');
        $this->filename = 'testCsvFile' . GenerateCsv::FILE_EXTENSION;
    }

    public function tearDown()
    {
        unlink($this->filename);
    }

    public function test_should_create_new_csv_file()
    {
        self::assertTrue(file_exists($this->filename));
    }

    public function test_should_return_proper_content()
    {
        $file = new Csv();
        $content = $file->load($this->filename)->getActiveSheet()->toArray();

        $expectedContent = [
            ['email@email.com'],
            ['john@email.com']
        ];

        self::assertSame($expectedContent, $content);
    }
}