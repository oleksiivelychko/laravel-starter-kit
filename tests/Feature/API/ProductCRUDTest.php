<?php

namespace Tests\Feature\API;

use App\Models\Category;
use App\Models\Product;
use App\Traits\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class ProductCRUDTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product()
    {
        Storage::fake('uploads');
        $file = UploadedFile::fake()->image('product.jpg');

        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json('POST', '/api/v1/products', [
            'name__en'          => 'Test product',
            'name__uk'          => 'Тестовий продукт',
            'price'             => 1.2,
            'description__en'   => 'Test product description',
            'description__uk'   => 'Тестовий продукт опис',
            'categories'        => [$category->id],
            'images'            => [$file]
        ], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $content = json_decode($response->content(), true);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['description', 'slug', 'name', 'updated_at', 'created_at', 'categories_ids', 'images'])
            ->assertExactJson($content);

        $this->assertEquals('testovii-produkt', $content['slug']);
        $this->assertEquals('Test product', Translation::translateObject($content['name'], 'en'));
        $this->assertEquals('Тестовий продукт', Translation::translateObject($content['name'], 'uk'));
        $this->assertEquals('Test product description', Translation::translateObject($content['description'], 'en'));
        $this->assertEquals('Тестовий продукт опис', Translation::translateObject($content['description'], 'uk'));
        $this->assertContains($category->id, $content['categories_ids']);
        Storage::disk('uploads')->assertExists('products_images/'.$content['id'].'/'.json_decode($content['images'], true)[0]);
    }

    public function test_update_product()
    {
        Storage::fake('uploads');
        $file1 = UploadedFile::fake()->image('product1.jpg');
        $file2 = UploadedFile::fake()->image('product2.jpg');

        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Category $category1 */
        $category1 = Category::factory()->create();
        /** @var Category $category2 */
        $category2 = Category::factory()->create();

        $response = $this->json('PUT', '/api/v1/products/'.$product->id, [
            'name__en'          => 'Test '.Translation::translateObject($product->name, 'en'),
            'name__uk'          => 'Тестовий '.Translation::translateObject($product->name, 'uk'),
            'price'             => 1.1,
            'description__en'   => 'Test '.Translation::translateObject($product->description, 'en'),
            'description__uk'   => 'Тестовий '.Translation::translateObject($product->description, 'en'),
            'categories'        => [$category1->id, $category2->id],
            'images'            => [$file1, $file2]
        ], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $content = json_decode($response->content(), true);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['description', 'slug', 'name', 'updated_at', 'created_at', 'categories_ids', 'images'])
            ->assertExactJson($content);

        $this->assertEquals('testovii-'.$product->slug, $content['slug']);
        $this->assertEquals('Test '.Translation::translateObject($product->name, 'en'), Translation::translateObject($content['name'], 'en'));
        $this->assertEquals('Тестовий '.Translation::translateObject($product->name, 'uk'), Translation::translateObject($content['name'], 'uk'));
        $this->assertEquals('Test '.Translation::translateObject($product->description, 'en'), Translation::translateObject($content['description'], 'en'));
        $this->assertEquals('Тестовий '.Translation::translateObject($product->description, 'en'), Translation::translateObject($content['description'], 'uk'));
        $this->assertContains($category1->id, $content['categories_ids']);
        $this->assertContains($category2->id, $content['categories_ids']);

        $images = array_filter(array_map(function ($image) {return !str_starts_with($image, '/tmp/') ? $image : false;},
            json_decode($content['images'], true)), function ($image) {return $image;});
        Storage::disk('uploads')->assertExists('products_images/'.$content['id'].'/'.array_shift($images));
        Storage::disk('uploads')->assertExists('products_images/'.$content['id'].'/'.array_shift($images));
    }

    public function test_delete_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->json('DELETE', '/api/v1/products/'.$product->id, [], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(['Product has been successfully deleted']);
    }

    public function test_get_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->json('GET', '/api/v1/products/'.$product->id, [], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['description', 'slug', 'name', 'updated_at', 'created_at', 'categories_ids', 'images'])
            ->assertExactJson(json_decode($response->content(), true));
    }

    public function test_get_products()
    {
        Product::factory()->create();
        Product::factory()->create();

        $response = $this->json('GET', '/api/v1/products/', [], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(json_decode($response->content(), true))
            ->assertJsonCount(2);
    }
}
