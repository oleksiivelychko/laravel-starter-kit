<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Throwable;


class ProductController extends Controller
{
    public function index(Request $request, Product $products, string $locale): Factory|View|Application
    {
        return view('admin.product.index', [
            'products' => $products->pagination($request, $locale)
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('admin.product.create', [
            'product' => new Product,
            'categories' => Category::select(['id','name'])->get()
        ]);
    }

    /**
     * @param StoreProductRequest $request
     * @return Redirector|Application|RedirectResponse
     * @throws Throwable
     */
    public function store(StoreProductRequest $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $product = new Product;
            if ($product->saveModel($validatedData)) {
                $request->session()->put('status', trans('admin.messages.model-create-success'));
                return redirect(route('product.edit', ['product' => $product, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, Product $product): Factory|View|Application
    {
        return view('admin.product.edit', [
            'product' => $product,
            'categories' => Category::select(['id','name'])->get()
        ]);
    }

    /**
     * @param StoreProductRequest $request
     * @param string $locale
     * @param Product $product
     * @return Redirector|Application|RedirectResponse
     * @throws Throwable
     */
    public function update(StoreProductRequest $request, string $locale, Product $product): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            if ($product->saveModel($validatedData)) {
                $request->session()->put('status', trans('admin.messages.model-update-success'));
            }
        }

        return redirect(route('product.edit', ['product' => $product, 'locale' => $locale]));
    }

    /**
     * @param string $locale
     * @param Product $product
     * @return Redirector|Application|RedirectResponse
     * @throws Exception
     */
    public function destroy(string $locale, Product $product): Redirector|Application|RedirectResponse
    {
        if ($product->delete()) {
            $dir = public_path('uploads') . "/{$product->getImagesFolder()}/{$product->id}";
            if (File::exists($dir)) {
                File::deleteDirectory($dir);
            }

            Session::put('status', trans('admin.messages.model-delete-success'));
        }

        return redirect(route('admin.products', ['locale' => $locale]));
    }

    public function deleteImage(string $locale, int $product_id, string $image): RedirectResponse
    {
        $product = Product::find($product_id);
        if ($product) {
            $images = $product->images_array;
            $deleteImage = array_search($image, $images);

            if ($deleteImage !== false) {
                $path = public_path('uploads') . "/{$product->getImagesFolder()}/$product_id/$image";
                if (File::exists($path)) {
                    File::delete($path);
                }
                Session::put('status', trans('admin.messages.model-update-success'));

                foreach ($product->getCropPresets() as $preset) {
                    $preset = $preset[0].'x'.$preset[1];
                    $path = public_path('uploads') . "/{$product->getImagesFolder()}/$product_id/".$preset.'_'.$image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }

                unset($images[$deleteImage]);
                $product->images = $images;
                $product->save();
            }
        }

        return back();
    }
}
