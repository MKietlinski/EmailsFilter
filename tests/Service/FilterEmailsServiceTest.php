<?php

namespace App\Tests\Service;

use App\Service\FilterEmailsService;
use App\Service\FilterFile\FilterCsv;
use App\Service\GenerateFile\GenerateCsv;
use App\Service\GenerateFile\GenerateTxt;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PHPUnit\Framework\TestCase;

class FilterEmailsServiceTest extends TestCase
{
    private $csvFileWithValidAddresses;
    private $csvFileWithInvalidAddresses;
    private $txtFileWithSummary;
    private $filterEmailsService;

    public function setUp()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'address1@email.com');
        $sheet->setCellValue('A2', 'address2@email.com');
        $sheet->setCellValue('A3', 'invalid.email.com');
        $csvFile = new Csv($spreadsheet);
        $csvFile->save('testFile.csv');

        $this->filterEmailsService = new FilterEmailsService(
            new FilterCsv('testFile'),
            new GenerateCsv()
        );

        $this->csvFileWithValidAddresses= FilterEmailsService::VALID_ADDRESSES_FILENAME . GenerateCsv::FILE_EXTENSION;
        $this->csvFileWithInvalidAddresses = FilterEmailsService::INVALID_ADDRESSES_FILENAME . GenerateCsv::FILE_EXTENSION;
        $this->txtFileWithSummary= FilterEmailsService::SUMMARY_FILENAME . GenerateTxt::FILE_EXTENSION;
    }

    public function tearDown()
    {
        unlink('testFile.csv');
        unlink($this->csvFileWithValidAddresses);
        unlink($this->csvFileWithInvalidAddresses);
        unlink($this->txtFileWithSummary);
    }

    public function test_should_create_two_csv_files_and_one_txt_file()
    {
        $this->filterEmailsService->filter();

        self::assertTrue(file_exists($this->csvFileWithValidAddresses));
        self::assertTrue(file_exists($this->csvFileWithInvalidAddresses));
        self::assertTrue(file_exists($this->txtFileWithSummary));
    }

    public function test_should_create_files_with_proper_contents()
    {
        $this->filterEmailsService->filter();

        $file = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $validAddressesFileContent = $file->load($this->csvFileWithValidAddresses)->getActiveSheet()->toArray();
        $invalidAddressesFileContent = $file->load($this->csvFileWithInvalidAddresses)->getActiveSheet()->toArray();

        self::assertSame([['address1@email.com'], ['address2@email.com']], $validAddressesFileContent);
        self::assertSame([['invalid.email.com']], $invalidAddressesFileContent);
        self::assertSame("Filtering summary:\r\nValid email addresses: 2\r\nInvalid email addresses: 1", file_get_contents($this->txtFileWithSummary));
    }
}