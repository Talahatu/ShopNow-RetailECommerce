<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Factory as FakerFactory;
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
        $faker = FakerFactory::create('id_ID');
        $type = ["regular", "seller"];
        $genderList = ["man", "woman", "other"];
        $gender = $genderList[$faker->numberBetween(0, 2)];
        $name = $faker->firstName($gender) . " " . $faker->lastName;
        $expl = explode(" ", $name);
        return [
            'name' => $name,
            'username' => $expl[0],
            'email' => "$expl[0].$expl[1]@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make($expl[0]), // password
            'remember_token' => Str::random(10),
            'phoneNumber' => "08" . $faker->unique()->numerify("##########"),
            "type" => $type[$faker->numberBetween(0, 1)],
            "gender" => $gender,
            "profilePicture" => $faker->imageUrl(360, 360, 'people', true),
            "saldo" => $faker->numberBetween(1, 9) * 10000,
            "autoTopup" => $faker->numberBetween(0, 1)
        ];
    }
}
