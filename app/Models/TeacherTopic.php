<?php

namespace App\Models;

use App\Models\Topic;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherTopic extends Model
{
    use HasFactory;
    protected $table = 'teacher_topics';
    protected $fillable = [
        'name',
        'teacher_id',
        'topic_id',
        'status'
    ];

    protected $with = ['topic', 'teacher'];
    public function topic() {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

}
