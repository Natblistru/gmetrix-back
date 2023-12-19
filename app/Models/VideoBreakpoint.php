<?php

namespace App\Models;

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


}
