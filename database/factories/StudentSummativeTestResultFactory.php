<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentTestResult;
use App\Models\SummativeTestItem;
use App\Models\SummativeTest;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\Student;

class StudentSummativeTestResultFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                         ->where('topic_id', $topicId)
                                         ->first()->id;
        $summativeTestId = SummativeTest::where('teacher_topic_id', $teacherTopictId)
                                         ->first()->id;
        $summativeTestItemId = SummativeTestItem::where('summative_test_id', $summativeTestId)
                                         ->where('order_number', $this->index+1)
                                         ->first()->id;
        $studentId = Student::firstWhere('name', 'userS1 student')->id;                                 

        $this->index++;

        return [
            'student_id' => $studentId,
            'summative_test_item_id' => $summativeTestItemId,

        ];
    }
}
