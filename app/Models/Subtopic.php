<?php

namespace App\Models;

use App\Models\TeacherTopic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subtopic extends Model
{
    use HasFactory;
    protected $table = 'subtopics';
    protected $fillable = [
        'name',
        'audio_path',       
        'teacher_topic_id',
        'order_number',
        'status'
    ];

    protected $with = ['teacher_topic'];
    public function teacher_topic() {
        return $this->belongsTo(TeacherTopic::class, 'teacher_topic_id', 'id');
    }
}
