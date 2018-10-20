<?php

namespace App\Service\GenerateFile;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class GenerateCsv implements GenerateFileInterface
{
    const FILE_EXTENSION = '.csv';

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function generate($data, string $filename): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->loadData($sheet, $data);
        $this->saveFile($spreadsheet, $filename);
    }

    private function loadData(Worksheet $sheet, array $data)
    {
        for ($i = 0; $i < count($data); $i++)
            $sheet->setCellValueByColumnAndRow(1,$i + 1, $data[$i]);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function saveFile(Spreadsheet $spreadsheet, string $filename): void
    {
        $csvFile = new Csv($spreadsheet);
        $csvFile->save($filename . self::FILE_EXTENSION);
    }
}