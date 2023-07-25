<?php

namespace Tests\Feature;

use App\Events\ReloadImportPageEvent;
use App\Jobs\ProcessImportJob;
use App\Models\Category;
use App\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ImportJobTest extends TestCase
{
    use RefreshDatabase;

    private array $importFiles = [
        'categories.csv' => 'text/csv',
        'categories.json' => 'application/json',
    ];

    public function testFakeImportCategories()
    {
        foreach ($this->importFiles as $filename => $mime) {
            $this->startFake($filename, $mime);
        }
    }

    public function testImportCategories()
    {
        foreach ($this->importFiles as $filename => $mime) {
            $this->start($filename, $mime);
            $this->refreshTestDatabase();
        }
    }

    private function prepare(string $filename, string $mime): string
    {
        $path = storage_path('app/public/import/').$filename;

        $file = new UploadedFile($path, $filename, $mime, null, true);
        $importFilename = 'import'.time().'.'.$file->getExtension();

        Storage::disk('uploads')->putFileAs('', $file, $importFilename);
        Storage::disk('uploads')->assertExists($importFilename);

        Auth::shouldReceive('id')->andReturn(1);

        return $importFilename;
    }

    private function startFake(string $filename, string $mime): void
    {
        $import = new Import();
        $import->init('category');

        $this->assertEquals(Import::STATE_INIT, $import->state);

        $importFilename = $this->prepare($filename, $mime);

        Bus::fake(ProcessImportJob::class);

        ProcessImportJob::dispatch(public_path('uploads/'.$importFilename), Category::class, $import, Auth::id());

        Bus::assertDispatched(ProcessImportJob::class);

        $this->assertEquals(Import::STATE_WORKS, $import->state);

        Storage::disk('uploads')->delete($importFilename);
        Storage::disk('uploads')->assertMissing($importFilename);
    }

    private function start(string $filename, string $mime): void
    {
        $import = new Import();
        $import->init('category');

        $this->assertEquals(Import::STATE_INIT, $import->state);

        $importFilename = $this->prepare($filename, $mime);

        $processImport = new ProcessImportJob(public_path('uploads/'.$importFilename), Category::class, $import, Auth::id());

        $this->assertEquals(Import::STATE_WORKS, $import->state);

        Auth::shouldReceive('hasResolvedGuards')->once()->andReturn(false);

        Event::fake(ReloadImportPageEvent::class);

        $processImport->handle();

        Event::assertDispatched(ReloadImportPageEvent::class);

        $this->assertEquals(Import::STATE_SUCCEED, $import->state);
        $this->assertEquals(2, $import->received);
        $this->assertEquals(0, $import->updated);
        $this->assertEquals(2, $import->created);
    }
}
