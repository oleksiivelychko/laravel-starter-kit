<?php

namespace Tests\Feature;

use App\Jobs\ProcessImportJob;
use App\Models\Category;
use App\Models\Import;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;


class ImportJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_categories_csv()
    {
        Bus::fake();

        $name = 'categories.csv';
        $path = storage_path('app/public/import/') . $name;
        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $importFilename = 'import'.time().'.'.$file->getExtension();
        Storage::disk('uploads')->putFileAs('', $file, $importFilename);
        $path = public_path('uploads/'.$importFilename);

        Storage::disk('uploads')->assertExists($importFilename);

        Auth::shouldReceive('id')->andReturn(1);

        $importModel = new Import;
        $importModel->init('categories');

        ProcessImportJob::dispatch($path, Category::class, $importModel, Auth::id());
        Bus::assertDispatched(ProcessImportJob::class);

        Storage::disk('uploads')->delete($importFilename);
        Storage::disk('uploads')->assertMissing($importFilename);
    }

    public function test_import_categories_json()
    {
        Bus::fake();

        $name = 'categories.json';
        $path = storage_path('app/public/import/') . $name;
        $file = new UploadedFile($path, $name, 'application/json', null, true);

        $importFilename = 'import'.time().'.'.$file->getExtension();
        Storage::disk('uploads')->putFileAs('', $file, $importFilename);
        $path = public_path('uploads/'.$importFilename);

        Storage::disk('uploads')->assertExists($importFilename);

        Auth::shouldReceive('id')->andReturn(1);

        $importModel = new Import;
        $importModel->init('categories');

        ProcessImportJob::dispatch($path, Category::class, $importModel, Auth::id());
        Bus::assertDispatched(ProcessImportJob::class);

        Storage::disk('uploads')->delete($importFilename);
        Storage::disk('uploads')->assertMissing($importFilename);
    }

    public function test_import_products_csv()
    {
        Bus::fake();

        $name = 'products.csv';
        $path = storage_path('app/public/import/') . $name;
        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $importFilename = 'import'.time().'.'.$file->getExtension();
        Storage::disk('uploads')->putFileAs('', $file, $importFilename);
        $path = public_path('uploads/'.$importFilename);

        Storage::disk('uploads')->assertExists($importFilename);

        Auth::shouldReceive('id')->andReturn(1);

        $importModel = new Import;
        $importModel->init('products');

        ProcessImportJob::dispatch($path, Product::class, $importModel, Auth::id());
        Bus::assertDispatched(ProcessImportJob::class);

        Storage::disk('uploads')->delete($importFilename);
        Storage::disk('uploads')->assertMissing($importFilename);
    }

    public function test_import_products_json()
    {
        Bus::fake();

        $name = 'products.json';
        $path = storage_path('app/public/import/') . $name;
        $file = new UploadedFile($path, $name, 'application/json', null, true);

        $importFilename = 'import'.time().'.'.$file->getExtension();
        Storage::disk('uploads')->putFileAs('', $file, $importFilename);
        $path = public_path('uploads/'.$importFilename);

        Storage::disk('uploads')->assertExists($importFilename);

        Auth::shouldReceive('id')->andReturn(1);

        $importModel = new Import;
        $importModel->init('products');

        ProcessImportJob::dispatch($path, Product::class, $importModel, Auth::id());
        Bus::assertDispatched(ProcessImportJob::class);

        Storage::disk('uploads')->delete($importFilename);
        Storage::disk('uploads')->assertMissing($importFilename);
    }
}
