<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentSubopicProgress;
use App\Models\Student;
use App\Models\Subtopic;

class StudentSubopicProgressFactory extends Factory
{
    private $index = 0;
    public function definition(): array  {
        $students = ['userS1 student',
                        'userS1 student', 
                        'userS2 student', 
                        'userS2 student',
                    ];
        $subtopics = ['Definiția mulțimilor',
                        'Modurile de reprezentare a mulțimii', 
                        'Adunarea și scăderea numerelor naturale. Proprietăți', 
                        'Înmulțirea numerelor naturale. Proprietăți. Factorul comun',
                    ];
        $studentId = Student::firstWhere('name', $students[$this->index])->id;
        $subtopictId = Subtopic::firstWhere('name', $subtopics[$this->index])->id;

        $this->index++;
        return [
            'student_id' => $studentId,
            'subtopic_id' => $subtopictId,
        ];
    }
}

