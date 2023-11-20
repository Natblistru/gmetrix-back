<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class StudentFactory extends Factory
{

    private $index = 0;

    public function definition(): array
    {
        $usersName = ['userS1', 'userS2'];

        $userId = User::firstWhere('name', $usersName[$this->index])->id;
        $nameT = $usersName[$this->index] . ' student';

        $this->index++;

        return [
            'name' => $nameT,
            'user_id' => $userId,
        ];
    }

    
}
