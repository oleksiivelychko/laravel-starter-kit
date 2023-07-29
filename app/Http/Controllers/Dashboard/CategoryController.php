<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index(Request $request, Category $categories, string $locale): Factory|View|Application
    {
        return view('dashboard.category.index', [
            'categories' => $categories->pagination($request, $locale),
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('dashboard.category.create')->with('category', new Category());
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreCategoryRequest $request): Redirector|RedirectResponse|Application
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $category = new Category();
            if ($category->saveModel($validatedData)) {
                $request->session()->put('status', trans('dashboard.messages.model-create-success'));

                return redirect(route('category.edit', ['category' => $category, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, Category $category): Factory|View|Application
    {
        return view('dashboard.category.edit')->with('category', $category);
    }

    /**
     * @throws \Throwable
     */
    public function update(StoreCategoryRequest $request, string $locale, Category $category): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            if ($category->saveModel($validatedData)) {
                $request->session()->put('status', trans('dashboard.messages.model-update-success'));
            }
        }

        return redirect(route('category.edit', ['category' => $category, 'locale' => $locale]));
    }

    public function destroy(Category $category, string $locale): Redirector|Application|RedirectResponse
    {
        $IDs = $category->selectUniqueProductIds();
        if ($category->delete()) {
            $category->deleteUnusedProducts($IDs);

            Session::put('status', trans('dashboard.messages.model-delete-success'));
        }

        return redirect(route('dashboard.categories', $locale));
    }
}
