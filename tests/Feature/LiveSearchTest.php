<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Traits\Translation;
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
            /**
             * TODO: replace with non-static method
             */
            'search' => Translation::translateObject($category->name)
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([['value', 'text']])
            ->assertExactJson(json_decode($response->content(), true));

        $content = json_decode($response->content(), true)[0];
        $this->assertEquals($category->id, $content['value']);
        $this->assertEquals(Translation::translateObject($category->name), $content['text']);
    }

    public function test_live_search_products()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->json('POST', '/'.app()->getLocale().'/ajax/live-search-product', [
            /**
             * TODO: replace with non-static method
             */
            'search' => Translation::translateObject($product->name)
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([['value', 'text']])
            ->assertExactJson(json_decode($response->content(), true));

        $content = json_decode($response->content(), true)[0];
        $this->assertEquals($product->id, $content['value']);
        /**
         * TODO: replace with non-static method
         */
        $this->assertEquals(Translation::translateObject($product->name), $content['text']);
    }
}
