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
        $types = ["quiz","dnd","check","words"];
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                         ->where('topic_id', $topicId)
                                         ->first()->id;
        $testId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
                                         ->where('type', $types[$this->index])
                                         ->first()->id;
        $testItemId = TestItem::where('type', $types[$this->index])
                                         ->first()->id;
        $summativeTestId = SummativeTest::where('teacher_topic_id', $teacherTopictId)
                                         ->first()->id;

        $this->index++;

        return [
            'order_number' => $this->index,
            'summative_test_id' => $summativeTestId,
            'test_item_id' => $testItemId
        ];
    }
}
