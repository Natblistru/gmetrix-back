<?php

namespace App\Models;

use App\Models\TeacherTopic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlipCard extends Model
{
    use HasFactory;
    protected $table = 'flip_cards';
    protected $fillable = [
        'task',
        'answer',
        'teacher_topic_id',
        'status'
    ];

    protected $with = ['teacher_topic'];
    public function teacher_topic() {
        return $this->belongsTo(TeacherTopic::class, 'teacher_topic_id', 'id');
    }
}
