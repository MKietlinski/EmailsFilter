<?php

namespace App\Service\FilterFile;

interface FilterFileInterface
{
    public function getValidAddresses(): array;

    public function getInvalidAddresses(): array;
}