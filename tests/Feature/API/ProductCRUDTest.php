<?php

namespace Tests\Feature\API;

use App\Helpers\LocaleHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ProductCRUDTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduct()
    {
        Storage::fake('uploads');
        $file = UploadedFile::fake()->image('product.jpeg');

        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json('POST', '/api/v1/products', [
            'name__en' => 'Test product',
            'name__uk' => 'Тестовий продукт',
            'price' => 1.2,
            'description__en' => 'Test product description',
            'description__uk' => 'Тестовий продукт опис',
            'categories' => [$category->id],
            'images' => [$file],
        ], [
            'X-API-KEY' => env('API_TOKEN'),
        ]);

        $content = json_decode($response->content(), true);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['description', 'slug', 'name', 'updated_at', 'created_at', 'categories_ids', 'images'])
            ->assertExactJson($content)
        ;

        $this->assertEquals('test-product', $content['slug']);
        $this->assertEquals('Test product', LocaleHelper::translateObject($content['name'], 'en'));
        $this->assertEquals('Тестовий продукт', LocaleHelper::translateObject($content['name'], 'uk'));
        $this->assertEquals('Test product description', LocaleHelper::translateObject($content['description'], 'en'));
        $this->assertEquals('Тестовий продукт опис', LocaleHelper::translateObject($content['description'], 'uk'));
        $this->assertContains($category->id, $content['categories_ids']);
        Storage::disk('uploads')->assertExists('products_images/'.$content['id'].'/'.json_decode($content['images'], true)[0]);
    }

    public function testUpdateProduct()
    {
        Storage::fake('uploads');
        $file1 = UploadedFile::fake()->image('product1.jpeg');
        $file2 = UploadedFile::fake()->image('product2.jpeg');

        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Category $category1 */
        $category1 = Category::factory()->create();

        /** @var Category $category2 */
        $category2 = Category::factory()->create();

        $response = $this->json('PUT', '/api/v1/products/'.$product->id, [
            'name__en' => 'Test '.LocaleHelper::translateObject($product->name, 'en'),
            'name__uk' => 'Тестовий '.LocaleHelper::translateObject($product->name, 'uk'),
            'price' => 1.1,
            'description__en' => 'Test '.LocaleHelper::translateObject($product->description, 'en'),
            'description__uk' => 'Тестовий '.LocaleHelper::translateObject($product->description, 'en'),
            'categories' => [$category1->id, $category2->id],
            'images' => [$file1, $file2],
        ], [
            'X-API-KEY' => env('API_TOKEN'),
        ]);

        $content = json_decode($response->content(), true);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['description', 'slug', 'name', 'updated_at', 'created_at', 'categories_ids', 'images'])
            ->assertExactJson($content)
        ;

        $this->assertEquals('test-'.$product->slug, $content['slug']);
        $this->assertEquals('Test '.LocaleHelper::translateObject($product->name, 'en'), LocaleHelper::translateObject($content['name'], 'en'));
        $this->assertEquals('Тестовий '.LocaleHelper::translateObject($product->name, 'uk'), LocaleHelper::translateObject($content['name'], 'uk'));
        $this->assertEquals('Test '.LocaleHelper::translateObject($product->description, 'en'), LocaleHelper::translateObject($content['description'], 'en'));
        $this->assertEquals('Тестовий '.LocaleHelper::translateObject($product->description, 'en'), LocaleHelper::translateObject($content['description'], 'uk'));
        $this->assertContains($category1->id, $content['categories_ids']);
        $this->assertContains($category2->id, $content['categories_ids']);

        $images = array_filter(array_map(
            function ($image) {return !str_starts_with($image, '/tmp/') ? $image : false; },
            json_decode($content['images'], true)
        ), function ($image) {return $image; });

        Storage::disk('uploads')->assertExists('products_images/'.$content['id'].'/'.array_shift($images));
        Storage::disk('uploads')->assertExists('products_images/'.$content['id'].'/'.array_shift($images));
    }

    public function testDeleteProduct()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->json('DELETE', '/api/v1/products/'.$product->id, [], [
            'X-API-KEY' => env('API_TOKEN'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(['Product has been successfully deleted'])
        ;
    }

    public function testGetProduct()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->json('GET', '/api/v1/products/'.$product->id, [], [
            'X-API-KEY' => env('API_TOKEN'),
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['description', 'slug', 'name', 'updated_at', 'created_at', 'categories_ids', 'images'])
            ->assertExactJson(json_decode($response->content(), true))
        ;
    }

    public function testGetProducts()
    {
        Product::factory()->create();
        Product::factory()->create();

        $response = $this->json('GET', '/api/v1/products/', [], [
            'X-API-KEY' => env('API_TOKEN'),
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(json_decode($response->content(), true))
            ->assertJsonCount(2)
        ;
    }
}
