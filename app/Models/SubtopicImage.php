<?php

namespace App\Models;

use App\Models\Subtopic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubtopicImage extends Model
{
    use HasFactory;
    protected $table = 'subtopic_images';
    protected $fillable = [
        'path',
        'subtopic_id',
        'status'
    ];

    protected $with = ['subtopic'];
    public function subtopic() {
        return $this->belongsTo(Subtopic::class, 'subtopic_id', 'id');
    }
}
