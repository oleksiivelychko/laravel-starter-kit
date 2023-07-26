<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    public function index(Request $request, Role $roles): Factory|View|Application
    {
        return view('dashboard.role.index', [
            'roles' => $roles->pagination($request),
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('dashboard.role.create')->with('role', new Role());
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:'.config('settings.schema.string_length'),
            'slug' => 'required|alpha_dash|unique:roles',
        ]);

        if ($validatedData) {
            $role = new Role();
            $role->fill($validatedData);
            if ($role->save()) {
                $request->session()->put('status', trans('dashboard.messages.model-create-success'));

                return redirect(route('role.edit', ['role' => $role, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(Role $role): Factory|View|Application
    {
        return view('dashboard.role.edit')->with('role', $role);
    }

    public function update(Request $request, Role $role, string $locale): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:'.config('settings.schema.string_length'),
            'slug' => 'required|alpha_dash|unique:roles,slug,'.$role->id,
        ]);

        if ($validatedData) {
            $role->fill($validatedData);
            if ($role->save()) {
                $request->session()->put('status', trans('dashboard.messages.model-update-success'));
            }
        }

        return redirect(route('role.edit', ['role' => $role, 'locale' => $locale]));
    }

    public function destroy(Role $role, string $locale): Redirector|Application|RedirectResponse
    {
        if ($role->delete()) {
            Session::put('status', trans('dashboard.messages.model-delete-success'));
        }

        return redirect(route('dashboard.roles', $locale));
    }
}
