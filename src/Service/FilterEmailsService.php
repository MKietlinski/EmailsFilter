<?php

namespace App\Service;

use App\Service\FilterFile\FilterFileInterface;
use App\Service\GenerateFile\GenerateFileInterface;
use App\Service\GenerateFile\GenerateTxt;

class FilterEmailsService
{
    const VALID_ADDRESSES_FILENAME = 'ValidAddresses';
    const INVALID_ADDRESSES_FILENAME = 'InvalidAddresses';
    const SUMMARY_FILENAME = 'FilterSummary';

    private $filterFileService;
    private $generateFileService;

    public function __construct(FilterFileInterface $filterFileService, GenerateFileInterface $generateFileService)
    {
        $this->filterFileService = $filterFileService;
        $this->generateFileService = $generateFileService;
    }

    public function filter(): void
    {
        $validAddresses = $this->filterFileService->getValidAddresses();
        $this->generateFileService->generate($validAddresses, self::VALID_ADDRESSES_FILENAME);

        $invalidAddresses = $this->filterFileService->getInvalidAddresses();
        $this->generateFileService->generate($invalidAddresses, self::INVALID_ADDRESSES_FILENAME);

        $generateTxtService = new GenerateTxt();
        $description = $this->getSummaryContent($validAddresses, $invalidAddresses);
        $generateTxtService->generate($description, self::SUMMARY_FILENAME);
    }

    private function getSummaryContent(array $validAddresses, array $invalidAddresses): string
    {
        return  'Filtering summary:' . PHP_EOL .
                'Valid email addresses: ' . count($validAddresses) . PHP_EOL .
                'Invalid email addresses: ' . count($invalidAddresses);
    }
}