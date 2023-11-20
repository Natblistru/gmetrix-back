<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FormativeTestItem;
use App\Models\FormativeTest;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\Test;
use App\Models\TestItem;


class FormativeTestItemFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $types = ["quiz", 
            "dnd", 
            "dnd", 
            "check", 
            "snap", 
            "dnd_group", 
            "dnd", 
            "words", 
            "dnd_chrono_double", 
            "dnd_chrono"
        ];
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
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                            ->where('topic_id', $topicId)
                                            ->first()->id;
        $formativeTestId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
                                            ->where('type', $types[$this->index])
                                            ->first()->id;

        $testItemId = TestItem::where('type', $types[$this->index])
                                ->where('task', $tasks[$this->index])
                                ->first()->id;

        $this->index++;
    
        return [
            'order_number' => 1,
            'formative_test_id' => $formativeTestId,
            'test_item_id' => $testItemId,
        ];
    }
}
