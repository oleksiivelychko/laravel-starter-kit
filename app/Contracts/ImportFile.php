<?php

namespace App\Contracts;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportFile
{
    public static function import(string $classname, UploadedFile $file): array;
}
