<?php

namespace Database\Seeders;

use App\Helpers\ImageHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends SchemaSeeder
{
    private const loremIpsumEN = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
    private const loremIpsumUK = 'Lorem Ipsum - це текст-"риба", що використовується в друкарстві та дизайні. Lorem Ipsum є, фактично, стандартною "рибою" аж з XVI сторіччя, коли невідомий друкар взяв шрифтову гранку та склав на ній підбірку зразків шрифтів. "Риба" не тільки успішно пережила п’ять століть, але й прижилася в електронному верстуванні, залишаючись по суті незмінною. Вона популяризувалась в 60-их роках минулого сторіччя завдяки виданню зразків шрифтів Letraset, які містили уривки з Lorem Ipsum, і вдруге - нещодавно завдяки програмам комп’ютерного верстування на кшталт Aldus Pagemaker, які використовували різні версії Lorem Ipsum.';

    private string $description = '';

    public function __construct()
    {
        $this->description = json_encode(['en' => self::loremIpsumEN, 'uk' => self::loremIpsumUK], JSON_UNESCAPED_UNICODE);

        parent::__construct(Product::class);

        DB::table('categories_products')->truncate();
    }

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        $category = Category::whereSlug('drinks')->first();
        $this->fakeProduct($category, ['en' => 'Coffee', 'uk' => 'Кава']);
        $this->fakeProduct($category, ['en' => 'Cappuccino', 'uk' => 'Капучино']);

        $category = Category::whereSlug('lunches')->first();
        $this->fakeProduct($category, ['en' => 'Sandwich', 'uk' => 'Сендвіч']);
        $this->fakeProduct($category, ['en' => 'Burger', 'uk' => 'Бургер']);

        $category = Category::whereSlug('pizzas')->first();
        $this->fakeProduct($category, ['en' => 'Mushroom Pizza', 'uk' => 'Піца з грибами']);
    }

    /**
     * @throws \Exception
     */
    private function fakeProduct(Category $category, array $name): void
    {
        $product = new Product();
        $product->price = random_int(10, 100);
        $product->name = json_encode($name, JSON_UNESCAPED_UNICODE);
        $product->slug = Str::slug($name['en']);
        $product->description = $this->description;
        $saved = $product->save();

        $product->categories()->attach($category->id);

        if ($saved) {
            $images = [];

            $productImgDir = storage_path("app/public/seeders/{$product->getImagesFolder()}/");
            $isNew = true;

            foreach (glob("{$productImgDir}{$category->slug}-{$product->slug}-*.jpeg") as $filepath) {
                $filepaths = explode('/', $filepath);
                $filename = $filepaths[count($filepaths) - 1];
                $this->moveAndCropImg($product, $filename, $isNew);
                $images[] = $filename;
                $isNew = false;
            }

            $product->images = json_encode($images, JSON_UNESCAPED_UNICODE);
            $product->save();
        }
    }

    private function moveAndCropImg(Product $product, string $imageName, $isNew = false): void
    {
        $productImgDir = public_path('uploads')."/{$product->getImagesFolder()}/{$product->id}/";

        if ($isNew && File::exists($productImgDir)) {
            File::deleteDirectory($productImgDir);
        }

        if (!File::exists($productImgDir)) {
            File::makeDirectory($productImgDir, 493, true);
        }

        File::copy(
            storage_path("app/public/seeders/{$product->getImagesFolder()}/{$imageName}"),
            $productImgDir.$imageName
        );

        ImageHelper::crop(
            $productImgDir.$imageName,
            "{$product->getImagesFolder()}/{$product->id}",
            [[200, 150], [900, 400]]
        );
    }
}
