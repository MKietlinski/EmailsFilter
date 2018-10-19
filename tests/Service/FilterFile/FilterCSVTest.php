<?php

namespace App\Tests\Service\FilterFile;

use App\Service\FilterFile\FilterCSV;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PHPUnit\Framework\TestCase;

class FilterCSVTest extends TestCase
{
    public function tearDown()
    {
        if (file_exists('addresses.csv'))
            unlink('addresses.csv');
    }

    /**
     * @dataProvider ValidEmailsProvider
     */
    public function test_should_return_only_valid_addresses(string $address)
    {
        $this->createCsvFileWithEmailAddress($address);

        $filter = new FilterCSV('addresses');

        self::assertContains($address, $filter->getValidAddresses());
    }

    public function ValidEmailsProvider()
    {
        return [
          ['email1@email.com'],
          [' email1@email.com '],
        ];
    }

    /**
     * @dataProvider InvalidEmailsProvider
     */
    public function test_should_return_only_invalid_addresses(string $address)
    {
        $this->createCsvFileWithEmailAddress($address);

        $filter = new FilterCSV('addresses');

        self::assertContains($address, $filter->getInvalidAddresses());
    }

    public function InvalidEmailsProvider()
    {
        return [
            ['email1'],
            ['email1.email.com'],
            ['email1.@email.com'],
            ['email1@.email.com'],
            ['ema il1@email.com'],
        ];
    }

    public function test_should_throw_error_if_file_doesnt_exist()
    {
        self::expectExceptionMessage('File "invalidFilename.csv" does not exist.');

        new FilterCSV('invalidFilename');
    }

    private function createCsvFileWithEmailAddress(string $address)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', $address);
        $csvFile = new Csv($spreadsheet);
        $csvFile->save('addresses.csv');
    }
}