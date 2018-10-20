<?php

namespace App\Service\GenerateFile;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class GenerateTxt implements GenerateFileInterface
{
    const FILE_EXTENSION = '.txt';

    public function generate($data, string $filename): void
    {
        $filename .= self::FILE_EXTENSION;
        $handle = fopen($filename, 'w');
        fwrite($handle, $data);
        fclose($handle);
    }
}