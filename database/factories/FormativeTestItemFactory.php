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
        $taskTypes = [
            ["type" => "quiz", "task" => "Alege afirmația corectă"],
            ["type" => "dnd", "task" => "Stabilește cauzele evenimentelor"],
            ["type" => "dnd", "task" => "Stabilește consecințele evenimentelor"],
            ["type" => "check", "task" => "Verifică corectitudinea afirmațiilor"],
            ["type" => "snap", "task" => "Formează perechi logice"],
            ["type" => "dnd_group", "task" => "Grupează elementele"],
            ["type" => "dnd", "task" => "Caracteristicile evenimentelor"],
            ["type" => "words", "task" => "Completează propoziția"],
            ["type" => "dnd_chrono_double", "task" => "Elaborează un fragment de text"],
            ["type" => "dnd_chrono", "task" => "Succesiunea cronologică a evenimentelor"],
        ];
        
        $taskType = $taskTypes[$this->index];
        $type = $taskType['type'];
        $task = $taskType['task'];

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                            ->where('topic_id', $topicId)
                                            ->first()->id;
        $formativeTestId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
                                            ->where('type', $type)
                                            ->first()->id;

        $testItemId = TestItem::where('type', $type)
                                ->where('task', $task)
                                ->first()->id;

        $this->index++;
    
        return [
            'order_number' => 1,
            'formative_test_id' => $formativeTestId,
            'test_item_id' => $testItemId,
        ];
    }
}
