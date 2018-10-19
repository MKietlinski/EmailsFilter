<?php

namespace App\Service;

use App\Service\FilterFile\FilterFileInterface;

class FilterEmailsService
{
    private $filterFileService;

    public function __construct(FilterFileInterface $filterFileService)
    {
        $this->filterFileService = $filterFileService;
    }

    public function filter(): void
    {
        $validAddresses = $this->filterFileService->getValidAddresses();
        $invalidAddresses = $this->filterFileService->getInvalidAddresses();
    }
}