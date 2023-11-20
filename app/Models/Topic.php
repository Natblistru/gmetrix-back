<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;

class Topic extends Model
{
    use HasFactory;
    public function tags() {
        return $this->morphMany(Tag::class, 'taggable');
    }
}
