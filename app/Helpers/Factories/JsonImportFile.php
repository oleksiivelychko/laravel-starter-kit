<?php

namespace App\Helpers\Factories;

use App\Helpers\JsonImport;
use App\Contracts\ImportFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class JsonImportFile implements ImportFile
{
    public static function import(string $classname, UploadedFile $file): array
    {
        $jsonDecode = json_decode(file_get_contents($file), true);
        return JsonImport::import($classname, $jsonDecode);
    }
}
