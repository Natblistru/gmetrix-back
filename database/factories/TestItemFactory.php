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
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Din lista prezentată selectați cauzele I Război Mondial:", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Din lista prezentată selectați consecințele I Război Mondial:", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Verifică corectitudinea afirmațiilor", "type" => "check", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Formează perechi logice", "type" => "snap", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Grupează elementele", "type" => "dnd_group", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Din lista prezentată selectați caracteristicile I Război Mondial:", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Completează propoziția", "type" => "words", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Elaborează un fragment de text", "type" => "dnd_chrono_double", "complexity" => "dificil", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Succesiunea cronologică a evenimentelor", "type" => "dnd_chrono", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Stabilește cauzele evenimentelor", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Stabilește consecințele evenimentelor", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Verifică corectitudinea afirmațiilor", "type" => "check", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Formează perechi logice", "type" => "snap", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Grupează elementele", "type" => "dnd_group", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Caracteristicile evenimentelor", "type" => "dnd", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Completează propoziția", "type" => "words", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Elaborează un fragment de text", "type" => "dnd_chrono_double", "complexity" => "dificil", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Succesiunea cronologică a evenimentelor", "type" => "dnd_chrono", "complexity" => "moderat", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
            ["task" => "Completează propoziția", "type" => "words", "complexity" => "simplu", "teacher_topic" => "Opțiunile politice în perioada neutralității (userT1 teacher)"],
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
