<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLesson extends Model
{
    use HasFactory;
    protected $table = 'video-lessons';

    protected $fillable = [
        'title',
        'source',
        'status',
        'subject_id',
    ];

    protected $with = ['subject'];
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
