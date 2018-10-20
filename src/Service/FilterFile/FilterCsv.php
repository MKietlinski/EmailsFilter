<?php

namespace App\Service\FilterFile;

use PhpOffice\PhpSpreadsheet\Reader\Csv;

class FilterCsv implements FilterFileInterface
{
    const FILE_EXTENSION = '.csv';

    private $validAddresses = [];
    private $invalidAddresses = [];

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __construct(string $filename)
    {
        $addresses = $this->transformCsvToArray($filename);
        $this->filterAddresses($addresses);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function transformCsvToArray(string $filename): array
    {
        $file = new Csv();
        return $file->load($filename . self::FILE_EXTENSION)->getActiveSheet()->toArray();
    }

    private function filterAddresses(array $addresses): void
    {
        foreach ($addresses as $address) {
            filter_var(trim($address[0]), FILTER_VALIDATE_EMAIL)
                ? $this->addValidAddress($address[0])
                : $this->addInvalidAddress($address[0]);
        }
    }

    private function addValidAddress(string $address): void
    {
        $this->validAddresses[] = $address;
    }

    private function addInvalidAddress($address): void
    {
        $this->invalidAddresses[] = $address;
    }

    public function getValidAddresses(): array
    {
        return $this->validAddresses;
    }

    public function getInvalidAddresses(): array
    {
        return $this->invalidAddresses;
    }
}