<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SubjectStudyLevel;
use App\Models\Subject;
use App\Models\StudyLevel;


class ChapterFactory extends Factory
{
    private $values = [
        'Mulţimi numerice', 'Rapoarte şi proporţii', 'Calcul algebric','Funcţii', 'Ecuaţii, inecuaţii, sisteme de ecuaţii, sisteme de inecuaţii','Geometrie',

        'Primul Război Mondial și formarea statului național român', 'Lumea în perioada interbelică', 'Relațiile internaționale în perioada interbelică','Al Doilea Război Mondial','Lumea postbelică','Lumea la sfârșitul secolului XX - începutul secolului XXI', 'Cultura și știința în perioada postbelică',

        'Perceperea identității lingvistice și culturale proprii în context național', 'Utilizarea limbii ca sistem și a normelor lingvistice în realizarea actelor communicative', 'Lectura și receptarea textelor literare și nonliterare','Producerea textelor scrise','Integrarea experiențelor lingvistice și de lectură în contexte şcolare şi de viață'
    ];
    private $index = 0;

    public function definition(): array
    {
        $subjects = ['Matematica', 'Matematica', 'Matematica', 'Matematica', 'Matematica', 'Matematica',
                     'Istoria', 'Istoria', 'Istoria', 'Istoria', 'Istoria', 'Istoria','Istoria',
                     'Limba română', 'Limba română', 'Limba română', 'Limba română', 'Limba română'];

        $orders = [1, 2, 3, 4, 5, 6,
                     1, 2, 3, 4, 5, 6,7,
                     1, 2, 3, 4, 5];

        $subjectId = Subject::firstWhere('name', $subjects[$this->index])->id;
        $studyLevelId = StudyLevel::firstWhere('name', 'Ciclu gimnazial')->id;
        $subjectStudyLevelId = SubjectStudyLevel::where('study_level_id', $studyLevelId)
                                                ->where('subject_id', $subjectId)
                                                ->first()->id;

        $name = $this->values[$this->index % count($this->values)];
        $columnOrder = $orders[$this->index];
        $this->index++;

        return [
            'name' => $name,
            'order_number' => $columnOrder,
            'subject_study_level_id' => $subjectStudyLevelId,
        ];
    }
}

