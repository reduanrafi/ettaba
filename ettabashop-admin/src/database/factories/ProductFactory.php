<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->text(10);
        return [
            'name_en' => $name,
            'name_bn' => $this->faker->text(10),
            'description_en' => $this->faker->text(100),
            'description_bn' => $this->faker->text(100),
            'category_id' => rand(1,4),
            'owner_id' => rand(1,4),
            'brand_id' => 1,
            'slug' => Str::slug($name),
            'featured_image'=>'',
            'sold_amount'=>rand(0,20),
            'vat_percent'=>rand(0,10),
            'discount'=>rand(0,10),
            'price_en'=>rand(0,1200),
            'price_bn'=>rand(0,1200),
            'quantity'=>rand(0,50),
            'unit'=>'KG',
            'is_sold_out'=>false,
            'is_featured'=>false,

        ];
    }
}