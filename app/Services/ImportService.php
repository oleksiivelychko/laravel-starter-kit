<?php

namespace App\Services;

use App\Contracts\ImportFile;
use App\Models\Import;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportService implements ImportFile
{
    private const SUCCESSFUL_MESSAGE = 'Operation has been finished.';

    private ?string $message = null;
    private ?string $status = null;
    private int $created = 0;
    private int $updated = 0;
    private int $received = 0;
    private int $time;

    public function __construct()
    {
        $this->time = time();
    }

    public function import(UploadedFile $file, string $classname): array
    {
        return match ($file->getExtension()) {
            'csv' => $this->importCsv($classname, $file),
            'json' => $this->importJson($classname, $file),
        };
    }

    public function importCsv(string $classname, UploadedFile $file): array
    {
        $headers = [];

        $csv = fopen($file, 'r');
        while (($line = fgetcsv($csv)) !== false) {
            if (!$headers) {
                $headers = array_values($line);

                continue;
            }

            ++$this->received;
            $model = $classname::whereId($line[0])->firstOrNew();

            try {
                $isExists = $model->exists ?: false;

                $values = array_map(function ($lineValue) {
                    if (str_contains($lineValue, '|')) {
                        return explode('|', $lineValue);
                    }

                    return $lineValue;
                }, array_values($line));

                $saved = $model->saveModel(array_combine($headers, $values));

                if ($saved && $isExists) {
                    ++$this->updated;
                }
                if ($saved && !$isExists) {
                    ++$this->created;
                }
            } catch (\Exception $exception) {
                $this->message = $exception->getMessage();
                $this->status = Import::STATE_FAILED;

                break;
            }
        }

        fclose($csv);

        return $this->response();
    }

    public function importJson(string $classname, UploadedFile $file): array
    {
        $data = json_decode(file_get_contents($file), true);

        foreach ($data as $jsonObject) {
            ++$this->received;
            $model = $classname::whereId($jsonObject['id'])->firstOrNew();

            try {
                $isExists = $model->exists ?: false;
                $saved = $model->saveModel($jsonObject);

                if ($saved && $isExists) {
                    ++$this->updated;
                }
                if ($saved && !$isExists) {
                    ++$this->created;
                }
            } catch (\Exception $exception) {
                $this->message = $exception->getMessage();
                $this->status = Import::STATE_FAILED;

                break;
            }
        }

        return $this->response();
    }

    private function response(): array
    {
        return [
            'seconds' => (string) time() - $this->time,
            'message' => $this->message ?: self::SUCCESSFUL_MESSAGE,
            'status' => $this->status ?: Import::STATE_SUCCEED,
            'created' => $this->created,
            'updated' => $this->updated,
            'received' => $this->received,
        ];
    }
}
