<?php

namespace Database\Factories;

use App\Models\TopProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TopProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => rand(8,200),
             'type_id'=>rand(1,4)
        ];
    }

}
