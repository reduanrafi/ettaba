<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

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
            'parent_id' => rand(0,10),
            'slug' => Str::slug($name),
            'image'=>'',
            'icon'=>'',

        ];
    }
}
