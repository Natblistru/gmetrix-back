<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Chapter;
use App\Models\ThemeLearningProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "path",
        "chapter_id"
    ];
    public function tags() {
        return $this->morphMany(Tag::class, 'taggable');
    }

    protected $with = ['chapter'];
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }

    public function themeLearningPrograms()
    {
        return $this->hasMany(ThemeLearningProgram::class, 'theme_id', 'id');
    }
}
