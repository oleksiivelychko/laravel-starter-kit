<?php

namespace Tests\Feature\API;

use App\Helpers\LocaleHelper;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CategoryCRUDTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_category()
    {
        $response = $this->json('POST', '/api/v1/categories', [
            'name__en'  => 'Test category',
            'name__uk'  => 'Тестова категорiя'
        ], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $content = json_decode($response->content(), true);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['parent_id', 'slug', 'name', 'updated_at', 'created_at'])
            ->assertExactJson($content);

        $this->assertEquals('testova-kategoriya', $content['slug']);
        $this->assertEquals('Test category', LocaleHelper::translateObject($content['name'], 'en'));
        $this->assertEquals('Тестова категорiя', LocaleHelper::translateObject($content['name'], 'uk'));
    }

    public function test_update_category()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json('PUT', '/api/v1/categories/'.$category->id, [
            'name__en'  => 'Test '.$category->name,
            'name__uk'  => 'Тестова '.$category->name
        ], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $content = json_decode($response->content(), true);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['parent_id', 'slug', 'name', 'updated_at', 'created_at'])
            ->assertExactJson($content);

        $this->assertEquals('Test '.$category->name, LocaleHelper::translateObject($content['name'], 'en'));
        $this->assertEquals('Тестова '.$category->name, LocaleHelper::translateObject($content['name'], 'uk'));
    }

    public function test_delete_category()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json('DELETE', '/api/v1/categories/'.$category->id, [], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(['Category has been successfully deleted']);
    }

    public function test_get_category()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json('GET', '/api/v1/categories/'.$category->id, [], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['parent_id', 'slug', 'name', 'updated_at', 'created_at'])
            ->assertExactJson(json_decode($response->content(), true));
    }

    public function test_get_categories()
    {
        Category::factory()->create();
        Category::factory()->create();

        $response = $this->json('GET', '/api/v1/categories/', [], [
            'X-API-KEY' => env('API_TOKEN')
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson(json_decode($response->content(), true))
            ->assertJsonCount(2);
    }
}
