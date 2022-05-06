<?php

namespace App\Jobs;

use App\Events\ReloadImportPageEvent;
use App\Helpers\Factories\CsvImportFile;
use App\Helpers\Factories\JsonImportFile;
use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $filepath;

    private string $classname;

    private Import $importModel;

    private int $userId;

    public function __construct(string $filepath, string $classname, Import $importModel, int $userId)
    {
        $this->filepath = env('HEROKU') ? config('settings.import.products_json') : $filepath;
        $this->classname = $classname;
        $this->importModel = $importModel;
        $this->userId = $userId;

        $this->importModel->setAttribute('state', Import::STATE_WORKS);
        $this->importModel->save();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = new UploadedFile($this->filepath, basename($this->filepath));

        $response = match ($file->getExtension()) {
            'csv' => CsvImportFile::import($this->classname, $file),
            'json' => JsonImportFile::import($this->classname, $file),
        };

        $this->importModel->setAttribute('state', $response['status']);
        $this->importModel->setAttribute('received', $response['received']);
        $this->importModel->setAttribute('created', $response['created']);
        $this->importModel->setAttribute('updated', $response['updated']);
        $this->importModel->save();

        File::delete($this->filepath);

        sleep(1);

        event(new ReloadImportPageEvent($this->userId));
    }

    public function fail($exception = null)
    {
        $this->importModel->setAttribute('state', Import::STATE_FAILED);
        $this->importModel->save();

        File::delete($this->filepath);
    }
}
