<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class RoleController extends Controller
{
    public function index(Request $request, Role $roles): Factory|View|Application
    {
        return view('admin.role.index', [
            'roles' => $roles->pagination($request)
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('admin.role.create')->with('role', new Role);
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:'.config('settings.schema.string_length'),
            'slug' => 'required|alpha_dash|unique:roles',
        ]);

        if ($validatedData) {
            $role = new Role;
            $role->fill($validatedData);
            if ($role->save()) {
                $request->session()->put('status', trans('admin.messages.model-create-success'));
                return redirect(route('role.edit', ['role' => $role, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, Role $role): Factory|View|Application
    {
        return view('admin.role.edit')->with('role', $role);
    }

    public function update(Request $request, string $locale, Role $role): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:'.config('settings.schema.string_length'),
            'slug' => 'required|alpha_dash|unique:roles,slug,'.$role->id,
        ]);

        if ($validatedData) {
            $role->fill($validatedData);
            if ($role->save()) {
                $request->session()->put('status', trans('admin.messages.model-update-success'));
            }
        }

        return redirect(route('role.edit', ['role' => $role, 'locale' => $locale]));
    }

    /**
     * @param string $locale
     * @param Role $role
     * @return Redirector|Application|RedirectResponse
     * @throws Exception
     */
    public function destroy(string $locale, Role $role): Redirector|Application|RedirectResponse
    {
        if ($role->delete()) {
            Session::put('status', trans('admin.messages.model-delete-success'));
        }

        return redirect(route('admin.roles', $locale));
    }
}
