<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'username' => $this->faker->userName,
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'), // Default password untuk testing
            'role' => 'user',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'postal' => $this->faker->postcode,
            'about' => $this->faker->paragraph,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user has sales role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sales()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'sales',
            ];
        });
    }
}
