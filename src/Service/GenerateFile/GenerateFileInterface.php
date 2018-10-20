<?php

namespace App\Service\GenerateFile;

interface GenerateFileInterface
{
    public function generate($data, string $filename): void;
}