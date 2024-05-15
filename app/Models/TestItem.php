<?php

namespace App\Models;

use App\Models\TestComlexity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestItem extends Model
{
    use HasFactory;
    protected $table = 'test_items';
    protected $fillable = [
        'task',
        'task_ro',
        'content',
        'type',
        'image_path',
        'test_complexity_id',
        'teacher_topic_id',
        'status'
    ];

    protected $with = ['teacher_topic', 'test_complexity'];
    public function teacher_topic() {
        return $this->belongsTo(TeacherTopic::class, 'teacher_topic_id', 'id');
    }

    public function test_complexity() {
        return $this->belongsTo(TestComlexity::class, 'test_complexity_id', 'id');
    }
    

}
