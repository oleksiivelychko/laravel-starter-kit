<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request, Product $products, string $locale): Factory|View|Application
    {
        return view('dashboard.product.index', [
            'products' => $products->pagination($request, $locale),
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('dashboard.product.create', [
            'product' => new Product(),
            'categories' => Category::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreProductRequest $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $product = new Product();
            if ($product->saveModel($validatedData)) {
                $request->session()->put('status', trans('dashboard.messages.model-create-success'));

                return redirect(route('product.edit', ['product' => $product, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, Product $product): Factory|View|Application
    {
        return view('dashboard.product.edit', [
            'product' => $product,
            'categories' => Category::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function update(StoreProductRequest $request, string $locale, Product $product): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            if ($product->saveModel($validatedData)) {
                $request->session()->put('status', trans('dashboard.messages.model-update-success'));
            }
        }

        return redirect(route('product.edit', ['product' => $product, 'locale' => $locale]));
    }

    public function destroy(Product $product, string $locale): Redirector|Application|RedirectResponse
    {
        if ($product->delete()) {
            $uploadDir = public_path('uploads')."/{$product->getImagesFolder()}/{$product->id}";
            if (File::exists($uploadDir)) {
                File::deleteDirectory($uploadDir);
            }

            Session::put('status', trans('dashboard.messages.model-delete-success'));
        }

        return redirect(route('dashboard.products', ['locale' => $locale]));
    }

    public function deleteImage(string $locale, int $productId, string $image): RedirectResponse
    {
        $product = Product::find($productId);
        if ($product) {
            $images = $product->images_array;
            $searchImage = array_search($image, $images);

            if (false !== $searchImage) {
                $path = public_path('uploads')."/{$product->getImagesFolder()}/{$productId}/{$image}";
                if (File::exists($path)) {
                    File::delete($path);
                }

                Session::put('status', trans('dashboard.messages.model-update-success'));

                foreach ($product->getCropPresets() as $preset) {
                    $preset = $preset[0].'x'.$preset[1];
                    $path = public_path('uploads')."/{$product->getImagesFolder()}/{$productId}/".$preset.'_'.$image;

                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }

                unset($images[$searchImage]);

                $product->images = $images;
                $product->save();
            }
        }

        return back();
    }
}
