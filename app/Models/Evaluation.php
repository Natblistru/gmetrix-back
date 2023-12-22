<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectStudyLevel;

class Evaluation extends Model
{
    use HasFactory;
    protected $table = 'evaluations';
    protected $fillable = [
        'name',
        'year',
        'subject_study_level_id',
        'type',
        'status'
    ];

    protected $with = ['subject_study_level'];
    public function subject_study_level() {
        return $this->belongsTo(SubjectStudyLevel::class, 'subject_study_level_id', 'id');
    }

}
