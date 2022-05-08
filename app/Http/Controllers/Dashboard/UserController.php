<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;


class UserController extends Controller
{
    private UserRepository $userService;

    public function __construct(UserRepository $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request, User $users): Factory|View|Application
    {
        return view('dashboard.user.index', [
            'users' => $users->pagination($request)
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('dashboard.user.create')->with('user', new User);
    }

    public function store(StoreUserRequest $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $user = new User;
            $user->fill($validatedData);

            $uploadedAvatar = null;
            if (Arr::get($validatedData, $user->getImageField())) {
                $uploadedAvatar = $request->file($user->getImageField());
            }

            if ($user->store($request->get('roles'), $request->get('permissions'), $uploadedAvatar)) {
                $request->session()->put('status', trans('dashboard.messages.model-create-success'));
                return redirect(route('user.edit', ['user' => $user, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, User $user): Factory|View|Application
    {
        return view('dashboard.user.edit')->with('user', $user);
    }

    public function update(StoreUserRequest $request, string $locale, User $user): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $user->fill($validatedData);

            $uploadedAvatar = null;
            if (Arr::get($validatedData, $user->getImageField())) {
                $uploadedAvatar = $request->file($user->getImageField());
            }

            if ($user->store($request->get('roles'), $request->get('permissions'), $uploadedAvatar)) {
                $request->session()->put('status', trans('dashboard.messages.model-update-success'));
            }
        }

        return redirect(route('user.edit', ['user'=>$user, 'locale'=>$locale]));
    }

    public function destroy(string $locale, User $user): Redirector|Application|RedirectResponse
    {
        if ($user->delete()) {
            $imagesDir = public_path('uploads')."/{$user->getImagesFolder()}/{$user->id}";
            if (File::exists($imagesDir)) {
                File::deleteDirectory($imagesDir);
            }
            Session::put('status', trans('dashboard.messages.model-delete-success'));
        }

        return redirect(route('dashboard.users', $locale));
    }
}
