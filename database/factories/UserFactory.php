<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        $type = ["regular", "seller"];
        $gender = ["man", "woman", "other"];
        return [
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make(explode(" ", $name)[0]), // password
            'remember_token' => Str::random(10),
            'phoneNumber' => "08" . $this->faker->unique()->numerify("##########"),
            "type" => $type[$this->faker->numberBetween(0, 1)],
            "gender" => $gender[$this->faker->numberBetween(0, 1)],
            "profilePicture" => $this->faker->imageUrl(360, 360, 'people', true),
            "saldo" => $this->faker->numberBetween(1, 9) * 10000,
            "autoTopup" => $this->faker->numberBetween(0, 1)
        ];
    }
}
