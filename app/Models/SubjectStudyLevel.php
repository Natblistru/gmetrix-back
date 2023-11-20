<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectStudyLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "path",
        "img",
        "study_level_id",
        "subject_id",
    ];
}
