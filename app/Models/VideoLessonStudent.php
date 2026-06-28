<?php

namespace App\Models;

use App\Models\Student;
use App\Models\VideoLesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLessonStudent extends Model
{
    use HasFactory;
    protected $table = 'video-lesson-students';

    protected $fillable = [
        'videoLesson_id',
        'student_id',
        'suma',
        'statut',
    ];

    public function videoLesson()
    {
        return $this->belongsTo(VideoLesson::class, 'videoLesson_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
