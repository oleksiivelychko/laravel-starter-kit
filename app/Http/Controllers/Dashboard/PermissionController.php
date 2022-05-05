<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;


class PermissionController extends Controller
{
    public function index(Request $request, Permission $permissions): Factory|View|Application
    {
        return view('admin.permission.index', [
            'permissions' => $permissions->pagination($request)
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('admin.permission.create')->with('permission', new Permission);
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:'.config('settings.schema.string_length'),
            'slug' => 'required|alpha_dash|unique:permissions',
        ]);

        if ($validatedData) {
            $permission = new Permission;
            $permission->fill($validatedData);
            if ($permission->save()) {
                $request->session()->put('status', trans('admin.messages.model-create-success'));
                return redirect(route('permission.edit', ['permission' => $permission, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, Permission $permission): Factory|View|Application
    {
        return view('admin.permission.edit')->with('permission', $permission);
    }

    public function update(Request $request, string $locale, Permission $permission): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:'.config('settings.schema.string_length'),
            'slug' => 'required|alpha_dash|unique:permissions,slug,'.$permission->id,
        ]);

        if ($validatedData) {
            $permission->fill($validatedData);
            if ($permission->save()) {
                $request->session()->put('status', trans('admin.messages.model-update-success'));
            }
        }

        return redirect(route('permission.edit', ['permission' => $permission, 'locale' => $locale]));
    }

    /**
     * @param string $locale
     * @param Permission $permission
     * @return Redirector|Application|RedirectResponse
     * @throws Exception
     */
    public function destroy(string $locale, Permission $permission): Redirector|Application|RedirectResponse
    {
        if ($permission->delete()) {
            Session::put('status', trans('admin.messages.model-delete-success'));
        }

        return redirect(route('admin.permissions', $locale));
    }
}
