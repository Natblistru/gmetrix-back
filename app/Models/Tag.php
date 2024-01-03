<?php

namespace App\Models;

use App\Models\SubjectStudyLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tags';
    protected $fillable = [
        'tag_name',
        'taggable_id',
        'taggable_type',
        'subject_study_level_id',
        'status'
    ];

    protected $with = ['subject_study_level'];
    public function subject_study_level() {
        return $this->belongsTo(SubjectStudyLevel::class, 'subject_study_level_id', 'id');
    }

    public function taggable() {
        return $this->morphTo();
    }
}
