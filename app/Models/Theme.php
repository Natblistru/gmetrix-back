<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;

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
}
