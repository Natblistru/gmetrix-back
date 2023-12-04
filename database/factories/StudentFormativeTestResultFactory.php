<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentTestResult;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\FormativeTest;
use App\Models\TestItem;
use App\Models\Student;

class StudentFormativeTestResultFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $types = ["quiz","dnd","check","words"];
        $tasks = [
            "Alege afirmația corectă", 
            "Stabilește cauzele evenimentelor",
            "Verifică corectitudinea afirmațiilor", 
            "Completează propoziția", 
        ];

        $id_test_form_items = [1,11,12,13,14];
        $id_test_form_item = $id_test_form_items[$this->index];
        // $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        // $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        // $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
        //                                  ->where('topic_id', $topicId)
        //                                  ->first()->id;
        // $testId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
        //                        ->where('type', $types[$this->index])
        //                                  ->first()->id;
        // $testItemId = TestItem::where('type', $types[$this->index])
        //                        ->where('task', $tasks[$this->index])
        //                        ->first()->id;
        $studentId = Student::firstWhere('name', 'userS1 student')->id;                                 
        $this->index++;

        return [
            'student_id' => $studentId,
            'formative_test_item_id' => $id_test_form_item,
        ];
    }
}
