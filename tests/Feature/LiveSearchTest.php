<?php

namespace Tests\Feature;

use App\Helpers\LocaleHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class LiveSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_live_search_categories()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->json('POST', '/'.app()->getLocale().'/ajax/live-search-category', [
            'search' => LocaleHelper::translateObject($category->name)
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([['value', 'text']])
            ->assertExactJson(json_decode($response->content(), true));

        $content = json_decode($response->content(), true)[0];
        $this->assertEquals($category->id, $content['value']);
        $this->assertEquals(LocaleHelper::translateObject($category->name), $content['text']);
    }

    public function test_live_search_products()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->json('POST', '/'.app()->getLocale().'/ajax/live-search-product', [
            'search' => LocaleHelper::translateObject($product->name)
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([['value', 'text']])
            ->assertExactJson(json_decode($response->content(), true));

        $content = json_decode($response->content(), true)[0];
        $this->assertEquals($product->id, $content['value']);
        $this->assertEquals(LocaleHelper::translateObject($product->name), $content['text']);
    }
}
