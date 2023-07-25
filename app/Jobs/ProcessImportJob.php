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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private string $filepath;

    private string $classname;

    private Import $import;

    private int $userId;

    public function __construct(string $filepath, string $classname, Import $import, int $userID)
    {
        $this->filepath = $filepath;
        $this->classname = $classname;
        $this->import = $import;
        $this->userId = $userID;

        $this->import->setAttribute('state', Import::STATE_WORKS);
        $this->import->save();
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

        $this->import->setAttribute('state', $response['status']);
        $this->import->setAttribute('received', $response['received']);
        $this->import->setAttribute('created', $response['created']);
        $this->import->setAttribute('updated', $response['updated']);
        $this->import->save();

        File::delete($this->filepath);

        sleep(2);

        event(new ReloadImportPageEvent($this->userId));
    }

    public function fail($exception = null): void
    {
        $this->import->setAttribute('state', Import::STATE_FAILED);
        $this->import->save();

        File::delete($this->filepath);
    }
}
