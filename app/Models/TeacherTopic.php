<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;

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

    protected $with = ['topic'];
    public function topic() {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

}
