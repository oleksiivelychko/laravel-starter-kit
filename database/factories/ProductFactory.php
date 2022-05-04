<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->realText(10);
        $description = $this->faker->text(100);
        $image = $this->faker->image();
        return [
            'name'          => json_encode(['en' => $name, 'uk' => $name], JSON_UNESCAPED_UNICODE),
            'slug'          => Str::slug($name),
            'price'         => $this->faker->randomDigit(),
            'description'   => json_encode(['en' => $description, 'uk' => $description], JSON_UNESCAPED_UNICODE),
            'images'        => json_encode([$image, $image], JSON_UNESCAPED_UNICODE)
        ];
    }
}
