<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubopicProgress extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "subtopic_id",
        "progress_percentage"
    ];
}