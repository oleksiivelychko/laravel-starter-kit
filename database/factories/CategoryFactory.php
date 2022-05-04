<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->realText(10);
        return [
            'name' => json_encode(['en' => $name, 'uk' => $name], JSON_UNESCAPED_UNICODE),
            'slug' => Str::slug($name)
        ];
    }
}
