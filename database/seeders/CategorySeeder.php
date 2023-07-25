<?php

namespace Database\Seeders;

use App\Models\Category;

class CategorySeeder extends SchemaSeeder
{
    public function __construct()
    {
        parent::__construct(Category::class);
    }

    public function run(): void
    {
        $category = new Category();
        $category->name = json_encode(['en' => 'Drinks', 'uk' => 'Напої'], JSON_UNESCAPED_UNICODE);
        $category->slug = 'drinks';
        $category->save();

        $parentCategory = new Category();
        $parentCategory->name = json_encode(['en' => 'Lunches', 'uk' => 'Обід'], JSON_UNESCAPED_UNICODE);
        $parentCategory->slug = 'lunches';
        $parentCategory->save();

        $category = new Category();
        $category->name = json_encode(['en' => 'Pizzas', 'uk' => 'Піци'], JSON_UNESCAPED_UNICODE);
        $category->slug = 'pizzas';
        $category->parent_id = $parentCategory->id;
        $category->save();
    }
}
