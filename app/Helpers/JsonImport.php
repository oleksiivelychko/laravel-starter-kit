<?php

namespace App\Helpers;

use App\Models\Import;
use Exception;


class JsonImport
{
    public static function import(string $classname, array $data): array
    {
        $time = time();
        $message = $status = null;
        $created = $updated = $received = 0;

        foreach ($data as $jsonObject) {
            $received++;
            $model = $classname::whereId($jsonObject['id'])->firstOrNew();

            try {
                $isExists = $model->exists ?: false;
                $saved = $model->saveModel($jsonObject);

                if ($saved && $isExists) {
                    $updated++;
                }
                if ($saved && !$isExists) {
                    $created++;
                }
            } catch (Exception $exception) {
                $message = $exception->getMessage();
                $status = Import::STATE_FAILED;
                break;
            }
        }

        return [
            'seconds'   => (string)time() - $time,
            'message'   => $message ?: 'Operation has been finished',
            'status'    => $status ?: Import::STATE_SUCCEED,
            'created'   => $created,
            'updated'   => $updated,
            'received'  => $received
        ];
    }
}
