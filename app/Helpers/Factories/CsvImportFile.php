<?php

namespace App\Helpers\Factories;

use App\Contracts\ImportFile;
use App\Models\Import as ImportModel;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class CsvImportFile implements ImportFile
{
    public static function import(string $classname, UploadedFile $file): array
    {
        $time = time();

        $message = $status = null;
        $created = $updated = $received = 0;
        $headers = [];

        $csv = fopen($file, 'r');
        while (($line = fgetcsv($csv)) !== false) {
            if (!$headers) {
                $headers = array_values($line);
                continue;
            }

            $received++;
            $model = $classname::whereId($line[0])->firstOrNew();

            try {
                $isExists = $model->exists ?: false;

                $values = array_map(function ($lineValue) {
                    if (str_contains($lineValue, '|')) {
                        return explode('|', $lineValue);
                    } else {
                        return $lineValue;
                    }
                }, array_values($line));

                $saved = $model->saveModel(array_combine($headers, $values));

                if ($saved && $isExists) {
                    $updated++;
                }
                if ($saved && !$isExists) {
                    $created++;
                }
            } catch (Exception $exception) {
                $message = $exception->getMessage();
                $status = ImportModel::STATE_FAILED;
                break;
            }
        }

        fclose($csv);

        return [
            'seconds'   => (string)time() - $time,
            'message'   => $message ?: 'Operation has been finished',
            'status'    => $status ?: ImportModel::STATE_SUCCEED,
            'created'   => $created,
            'updated'   => $updated,
            'received'  => $received
        ];
    }
}
