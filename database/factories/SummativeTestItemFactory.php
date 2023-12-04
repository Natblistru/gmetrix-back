<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SummativeTestItem;
use App\Models\SummativeTest;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\FormativeTest;
use App\Models\TestItem;

class SummativeTestItemFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $summativetests = [
            ["order" => 1, "summative_test_id" => 1, "test_item_id" => 4],
            ["order" => 2, "summative_test_id" => 1, "test_item_id" => 8],
            ["order" => 3, "summative_test_id" => 1, "test_item_id" => 21],
            ["order" => 4, "summative_test_id" => 1, "test_item_id" => 8],
            ["order" => 1, "summative_test_id" => 2, "test_item_id" => 17],
            ["order" => 2, "summative_test_id" => 2, "test_item_id" => 8],
            ["order" => 3, "summative_test_id" => 2, "test_item_id" => 21],
            ["order" => 4, "summative_test_id" => 2, "test_item_id" => 8],
        ];

        $summativeTest = $summativetests[$this->index];
        
        // $types = ["quiz","dnd","check","words"];
        // $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        // $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        // $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
        //                                  ->where('topic_id', $topicId)
        //                                  ->first()->id;
        // $testItemId = TestItem::where('type', $types[$this->index])
        //                                  ->first()->id;
        // $summativeTestId = SummativeTest::where('teacher_topic_id', $teacherTopictId)
        //                                  ->first()->id;

        $this->index++;

        return [
            'order_number' => $summativeTest["order"],
            'summative_test_id' => $summativeTest["summative_test_id"],
            'test_item_id' => $summativeTest["test_item_id"]
        ];
    }
}
