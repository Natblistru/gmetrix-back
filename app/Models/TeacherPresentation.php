<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPresentation extends Model
{
    use HasFactory;
    protected $table = 'teacher_theme_videos';
    protected $fillable = [
        'name',
        'teacher_id',
        'path',
        'theme_learning_program_id',
        'status'
    ];

    protected $with = ['theme_learning_program'];

    public function theme_learning_program() {
        return $this->belongsTo(ThemeLearningProgram::class, 'theme_learning_program_id', 'id');
    }
}
