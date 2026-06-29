<?php

namespace App\Models;

use App\Models\Student;
use App\Models\VideoLesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    use HasFactory;
    
    protected $table = 'consultation-requests';

    protected $fillable = [
        'videoLesson_id',
        'student_id',
        'lesson_title',
        'name',
        'phone',
        'email',
        'suma',        
        'scheduled_at',
        'status',
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
