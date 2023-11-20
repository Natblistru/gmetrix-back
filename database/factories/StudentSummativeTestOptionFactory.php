<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudentSummativeTestOption;
use App\Models\SummativeTestItem;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Topic;
use App\Models\SummativeTest;
use App\Models\TestItem;
use App\Models\TestItemOption;

class StudentSummativeTestOptionFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $options = [
            "România a intrat în Primul Război Mondial în anul 1916",
           "România a intrat în Primul Război Mondial în anul 1916",
           "Regele României în timpul Primului Război Mondial a fost Carol I",
           "În perioada ;<1914>;-;<1916>; România a fost ;<neutră>;, deși avea un tratat de alianță cu ;<Tripla Alianță>;. Au existat dezbateri în țară privind participarea la război, iar în cele din urmă, pe ;<4 august 1916>;, România a semnat un tratat de alianță cu ;<Antanta>;, care prevedea eliberarea ;<Transilvaniei>; și realizarea unității naționale."
        ];
        $types = ["quiz","check","check","words"];
        $tasks = [
            "Alege afirmația corectă", 
            "Verifică corectitudinea afirmațiilor", 
            "Verifică corectitudinea afirmațiilor", 
            "Completează propoziția", 
        ];
        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                                        ->where('topic_id', $topicId)
                                        ->first()->id;
        $summativeTestId = SummativeTest::where('teacher_topic_id', $teacherTopictId)
                                        ->first()->id;

        $testItemId = TestItem::where('type', $types[$this->index])
                            ->where('task', $tasks[$this->index])
                            ->first()->id;
        $studentId = Student::firstWhere('name', 'userS1 student')->id;

        $summativeTestItemId = SummativeTestItem::where('summative_test_id', $summativeTestId) ->where('test_item_id', $testItemId)
                                        ->first()->id;

        $testItemOptionId = TestItemOption::where('option', $options[$this->index])
                            ->where('test_item_id', $testItemId)
                                        ->first()->id;

        $this->index++;

        return [
            'student_id' => $studentId,
            'summative_test_item_id' => $summativeTestItemId,
            'test_item_option_id' => $testItemOptionId,

        ];
    }
}
