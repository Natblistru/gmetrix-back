<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TestItem;
use App\Models\FormativeTest;
use App\Models\TestComlexity;
use App\Models\TeacherTopic;
use App\Models\Teacher;
use App\Models\Topic;

class TestItemFactory extends Factory
{
    private $index = 0;
    public function definition(): array
    {



        $testItems = [
            ["task" => "Alege afirmația corectă (Cand România a intrat în I RM)", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Selectați cauzele I Război Mondial:", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Selectați consecințele I Război Mondial:", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Verifică corectitudinea afirmațiilor (intrarea Romaniei  in I RM, Tratatul Trianon)", "type" => "check", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Formează perechi logice (Regi si generali)", "type" => "snap", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Grupează elementele (Antanta si Puterule Centrale)", "type" => "dnd_group", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Selectați caracteristicile I Război Mondial:", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Completează propoziția (Romania in I RM)", "type" => "words", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Elaborează un fragment de text (Romania in RM)", "type" => "dnd_chrono_double", "complexity" => "dificil", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Succesiunea cronologică a evenimentelor (Romania in RM)", "type" => "dnd_chrono", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă (România a I RM de partea ..)", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă (Batalia de la Turtucaia)", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă (Batalia de la Marasti)", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă (Tratatului de la Trianon)", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Stabilește cauzele cauzele neutralității României la începutil I Război Mondial", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Stabilește consecințele intrării Romaniei în I Război Mondial", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Verifică corectitudinea afirmațiilor (intrarea in I RM a Romaniei, Tratatul Trianon)", "type" => "check", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Formează perechi logice (Generali si regi)", "type" => "snap", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Grupează elementele (Puterule Centrale si Antanta)", "type" => "dnd_group", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Selectați caracteristicile I RM", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Completează propoziția (potato)", "type" => "words", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Elaborează un fragment de text (Romania in Razboi Mondial)", "type" => "dnd_chrono_double", "complexity" => "dificil", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Succesiunea cronologică a evenimentelor (Romania in Razboi Mondial)", "type" => "dnd_chrono", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Completează propoziția (apple)", "type" => "words", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
        ];

        $testItem = $testItems[$this->index];
        
        $task = $testItem['task'];
        $complexity = $testItem['complexity'];
        $type = $testItem['type'];
        $topic = $testItem['teacher_topic'];

        $complexityId = TestComlexity::where('name', $complexity)
                                         ->first()->id;
        $teacherTopicId = TeacherTopic::where('name', $topic)
                                         ->first()->id;

        $this->index++;

        return [
            'task' => $task,
            'type' =>$type,
            'test_complexity_id' =>$complexityId,
            'teacher_topic_id' => $teacherTopicId,
        ];
    }
}
