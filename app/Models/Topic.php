<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\ThemeLearningProgram;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'topics';
    public function tags() {
        return $this->morphMany(Tag::class, 'taggable');
    }


    protected $fillable = [
        'name',
        'path',
        'order_number',
        'theme_learning_program_id',
        'status'
    ];

    protected $with = ['theme_learning_program'];
    public function theme_learning_program() {
        return $this->belongsTo(ThemeLearningProgram::class, 'theme_learning_program_id', 'id');
    }

    public function teacherTopics()
    {
        return $this->hasMany(TeacherTopic::class, 'topic_id', 'id');
    }
}
