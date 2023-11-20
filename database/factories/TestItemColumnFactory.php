<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TestItemColumn;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\FormativeTest;
use App\Models\TestItem;

class TestItemColumnFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {
        $tasks = [
            "Stabilește cauzele evenimentelor", 
            "Stabilește cauzele evenimentelor",
            "Stabilește consecințele evenimentelor", 
            "Stabilește consecințele evenimentelor", 
            "Grupează elementele", 
            "Grupează elementele", 
            "Grupează elementele", 
            "Caracteristicile evenimentelor", 
            "Caracteristicile evenimentelor", 
            "Elaborează un fragment de text", 
            "Elaborează un fragment de text", 
            "Succesiunea cronologică a evenimentelor"
        ];

        $titles = [//cauze
            'Lista variantelor', 'Cauzele',
            //consecinte
            'Lista variantelor', 'Consecintele',
            //group
            'Tarile', 'Puterile centrale', 'Antanta',
            //caracteristica
            'Lista variantelor', 'Caracteristicile',
            //chronoDouble
            'Evenimentele', 'Text in ordine cronoligică',
            //chrono
            'Evenimentele',

            ];
            $columnOrders = [//cauze
                0, 1,
                //consecinte
                0, 1,
                //group
                0, 1, 2,
                //caracteristica
                0, 1,
                //chronoDouble
                0, 1,
                //chrono
                0,
                ];


        $types = ["dnd","dnd","dnd","dnd","dnd_group","dnd_group","dnd_group","dnd","dnd","dnd_chrono_double","dnd_chrono_double","dnd_chrono"];

        $title = $titles[$this->index];
        $type = $types[$this->index];
        $columnOrder = $columnOrders[$this->index];

        $teacherId = Teacher::firstWhere('name', 'userT1 teacher')->id;
        $topicId = Topic::firstWhere('name', 'Opțiunile politice în perioada neutralității')->id;

        $teacherTopictId = TeacherTopic::where('teacher_id', $teacherId)
                            ->where('topic_id', $topicId)
                            ->first()->id;
        $testId = FormativeTest::where('teacher_topic_id', $teacherTopictId)
                            ->where('type', $type)
                            ->first()->id;
        $testItemId = TestItem::where('type', $type)
                                ->where('task', $tasks[$this->index])
                            ->first()->id;

        $this->index++;


        return [
            'order_number' => $columnOrder,
            'title' => $title,
            'test_item_id' => $testItemId,
        ];
    }
}
