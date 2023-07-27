<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ImportHandler;
use App\Models\Import;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ImportController extends Controller
{
    public function index(): View|Factory|Application
    {
        return view('dashboard.import.index', [
            'imports' => Import::latest()->paginate(config('settings.schema.pagination_limit', 10)),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function post(Request $request): RedirectResponse
    {
        $files = $request->file();
        if (!$files) {
            $validator = Validator::make([], []);
            $validator->errors()->add('import', trans('dashboard.messages.file-not-uploaded'));

            throw new ValidationException($validator);
        }

        $files = $request->file();
        $filename = array_key_first($files);
        $file = $request->file($filename);

        $validator = Validator::make([
            'import' => strtolower($file->clientExtension()),
            'entity' => $request->get('entity'),
        ], [
            'import' => 'required|in:csv,json',
            'entity' => 'required', Rule::in(Import::ALLOWED_ENTITIES),
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $importFilename = 'import'.time().'.'.$file->clientExtension();
        Storage::disk('uploads')->putFileAs('', $file, $importFilename);
        $path = public_path('uploads/'.$importFilename);

        $classname = '\\App\\Models\\'.ucfirst($request->post('entity'));
        if (!class_exists($classname)) {
            $validator = Validator::make([], []);
            $validator->errors()->add('entity', trans('dashboard.messages.class-not-found'));

            throw new ValidationException($validator);
        }

        $import = new Import();
        $import->init($request->get('entity'));

        ImportHandler::dispatch($path, $classname, $import, Auth::id());

        return redirect()->back();
    }
}
