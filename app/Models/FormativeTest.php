<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormativeTest extends Model
{
    use HasFactory;
    protected $table = 'formative_tests';
    protected $fillable = [
        'order_number',
        'title',
        'path',
        'type',
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
