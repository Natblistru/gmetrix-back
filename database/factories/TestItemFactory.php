<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TestItem;
use App\Models\FormativeTest;
use App\Models\TestComlexity;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;

class TestItemFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $tasks = ["Alege afirmația corectă", 
                "Stabilește cauzele evenimentelor", 
                "Stabilește consecințele evenimentelor", 
                "Verifică corectitudinea afirmațiilor", 
                "Formează perechi logice", 
                "Grupează elementele", 
                "Caracteristicile evenimentelor", 
                "Completează propoziția", 
                "Elaborează un fragment de text", 
                "Succesiunea cronologică a evenimentelor"
            ];
        $complexities = ["simplu", 
            "moderat", 
            "moderat", 
            "simplu", 
            "simplu", 
            "simplu", 
            "moderat", 
            "simplu", 
            "dificil", 
            "moderat"
           ];

        $types = ["quiz","dnd","dnd","check", "snap", "dnd_group", "dnd", "words", "dnd_chrono_double", "dnd_chrono" ];
        $type = $types[$this->index];
        $task = $tasks[$this->index];
        $complexityId = TestComlexity::where('name', $complexities[$this->index])
                                         ->first()->id;

        $this->index++;

        return [
            'task' => $task,
            'type' =>$type,
            'test_complexity_id' =>$complexityId,
        ];
    }
}
