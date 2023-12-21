<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Video;
use App\Models\ThemeLearningProgram;

class TeacherThemeVideo extends Model
{
    use HasFactory;
    protected $table = 'teacher_theme_videos';
    protected $fillable = [
        'name',
        'teacher_id',
        'video_id',
        'theme_learning_program_id',
        'status'
    ];

    protected $with = ['video', 'theme_learning_program'];
    public function video() {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }

    public function theme_learning_program() {
        return $this->belongsTo(ThemeLearningProgram::class, 'theme_learning_program_id', 'id');
    }


}
