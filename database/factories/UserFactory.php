<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {

        $usersInfo = [
            ["name" => 'userT1', "email" => 'npfeffer@example.com', "password" => '12345678', "role_as" => 0],
            ["name" => 'userT2', "email" => 'tremblay.alayna@example.org', "password" => '12345678', "role_as" => 0],
            ["name" => 'userS1', "email" => 'bayer.kevin@example.net', "password" => '12345678', "role_as" => 0],        
            ["name" => 'userS2', "email" => 'deron.okon@example.org', "password" => '12345678', "role_as" => 0],  
            ["name" => 'test', "email" => 'test@gmail.com', "password" => '12345678', "role_as" => 0], 
            ["name" => 'admin', "email" => 'admin@gmail.com', "password" => '12345678', "role_as" => 1], 
        ];

        $userInfo = $usersInfo[$this->index];

        $this->index++;

        return [
            'name' => $userInfo['name'],
            'email' => $userInfo['email'],
            'password' => $userInfo['password'],
            'role_as' => $userInfo['role_as'],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
