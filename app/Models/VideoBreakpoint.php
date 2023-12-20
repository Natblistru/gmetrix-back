<?php

namespace App\Models;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoBreakpoint extends Model
{
    use HasFactory;
    protected $table = 'video_breakpoints';
    protected $fillable = [
        'name',
        'time',
        'seconds',
        'video_id',
        'status'
    ];

    protected $with = ['video'];
    public function video() {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }


}
