<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TeacherFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $usersName = ['userT1', 'userT2'];

        $userId = User::firstWhere('name', $usersName[$this->index])->id;
        $nameT = $usersName[$this->index] . ' teacher';

        $this->index++;
        return [
            'name' => $nameT,
            'user_id' => $userId,
        ];
    }
}

