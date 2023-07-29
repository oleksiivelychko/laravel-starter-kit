<?php

namespace App\Contracts;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportFile
{
    public function importCsv(string $classname, UploadedFile $file): array;

    public function importJson(string $classname, UploadedFile $file): array;
}
