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
            ["type" => "quiz", "task" => "Alege afirmația corectă", "test_item_id" => 1, "order" => 1],
            ["type" => "dnd", "task" => "Stabilește cauzele evenimentelor", "test_item_id" => 2, "order" => 1],
            ["type" => "dnd", "task" => "Stabilește consecințele evenimentelor", "test_item_id" => 3, "order" => 1],
            ["type" => "check", "task" => "Verifică corectitudinea afirmațiilor", "test_item_id" => 4, "order" => 1],
            ["type" => "snap", "task" => "Formează perechi logice", "test_item_id" => 5, "order" => 1],
            ["type" => "dnd_group", "task" => "Grupează elementele", "test_item_id" => 6, "order" => 1],
            ["type" => "dnd", "task" => "Caracteristicile evenimentelor", "test_item_id" => 7, "order" => 1],
            ["type" => "words", "task" => "Completează propoziția", "test_item_id" => 8, "order" => 1],
            ["type" => "dnd_chrono_double", "task" => "Elaborează un fragment de text", "test_item_id" => 9, "order" => 1],
            ["type" => "dnd_chrono", "task" => "Succesiunea cronologică a evenimentelor", "test_item_id" => 10, "order" => 1],
            ["type" => "quiz", "task" => "Alege afirmația corectă", "test_item_id" => 11, "order" => 2], 
            ["type" => "quiz", "task" => "Alege afirmația corectă", "test_item_id" => 12, "order" => 3],        
            ["type" => "quiz", "task" => "Alege afirmația corectă", "test_item_id" => 13, "order" => 4],        
            ["type" => "quiz", "task" => "Alege afirmația corectă", "test_item_id" => 14, "order" => 5],               
        ];
        
        $taskType = $taskTypes[$this->index];
        $type = $taskType['type'];
        $task = $taskType['task'];
        $testItemId = $taskType['test_item_id'];
        $order = $taskType['order'];


        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                            ->where('topic_id', $topicId)
                                            ->first()->id;
        $formativeTestId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
                                            ->where('type', $type)
                                            ->first()->id;

        // $testItemId = TestItem::where('type', $type)
        //                         ->where('task', $task)
        //                         ->first()->id;

        $this->index++;
    
        return [
            'order_number' =>  $order,
            'formative_test_id' => $formativeTestId,
            'test_item_id' => $testItemId,
        ];
    }
}
