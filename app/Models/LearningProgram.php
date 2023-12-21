<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectStudyLevel;


class LearningProgram extends Model
{
    use HasFactory;
    protected $table = 'learning_programs';
    protected $fillable = [
        'name',
        'year',
        'subject_study_level_id',
        'status'
    ];

    // protected $with = ['subject_study_level'];
    // public function subject_study_level() {
    //     return $this->belongsTo(LearningProgram::class, 'subject_study_level_id', 'id');
    // }

}
