<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create('id_ID');
        return [
            "user_id" => 1,
            "name" => $this->faker->word,
            "phoneNumber" => "08" . $faker->unique()->numerify("##########"),
            "address" => $faker->address(),
            "lat" => $faker->latitude,
            "long" => $faker->longitude,
            "logoImage" => $faker->imageUrl(360, 360, 'people', true),
        ];
    }
}
