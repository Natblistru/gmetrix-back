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
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu"],
            ["task" => "Din lista prezentată selectați cauzele I Război Mondial:", "type" => "dnd", "complexity" => "moderat"],
            ["task" => "Din lista prezentată selectați consecințele I Război Mondial:", "type" => "dnd", "complexity" => "moderat"],
            ["task" => "Verifică corectitudinea afirmațiilor", "type" => "check", "complexity" => "simplu"],
            ["task" => "Formează perechi logice", "type" => "snap", "complexity" => "simplu"],
            ["task" => "Grupează elementele", "type" => "dnd_group", "complexity" => "simplu"],
            ["task" => "Din lista prezentată selectați caracteristicile I Război Mondial:", "type" => "dnd", "complexity" => "moderat"],
            ["task" => "Completează propoziția", "type" => "words", "complexity" => "simplu"],
            ["task" => "Elaborează un fragment de text", "type" => "dnd_chrono_double", "complexity" => "dificil"],
            ["task" => "Succesiunea cronologică a evenimentelor", "type" => "dnd_chrono", "complexity" => "moderat"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu"],
            ["task" => "Alege afirmația corectă", "type" => "quiz", "complexity" => "simplu"],
            ["task" => "Stabilește cauzele evenimentelor", "type" => "dnd", "complexity" => "moderat"],
            ["task" => "Stabilește consecințele evenimentelor", "type" => "dnd", "complexity" => "moderat"],
            ["task" => "Verifică corectitudinea afirmațiilor", "type" => "check", "complexity" => "simplu"],
            ["task" => "Formează perechi logice", "type" => "snap", "complexity" => "simplu"],
            ["task" => "Grupează elementele", "type" => "dnd_group", "complexity" => "simplu"],
            ["task" => "Caracteristicile evenimentelor", "type" => "dnd", "complexity" => "moderat"],
            ["task" => "Completează propoziția", "type" => "words", "complexity" => "simplu"],
            ["task" => "Elaborează un fragment de text", "type" => "dnd_chrono_double", "complexity" => "dificil"],
            ["task" => "Succesiunea cronologică a evenimentelor", "type" => "dnd_chrono", "complexity" => "moderat"],
            ["task" => "Completează propoziția", "type" => "words", "complexity" => "simplu"],
        ];

        $testItem = $testItems[$this->index];
        
        $task = $testItem['task'];
        $complexity = $testItem['complexity'];
        $type = $testItem['type'];

        $complexityId = TestComlexity::where('name', $complexity)
                                         ->first()->id;

        $this->index++;

        return [
            'task' => $task,
            'type' =>$type,
            'test_complexity_id' =>$complexityId,
        ];
    }
}
