<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEvaluationSolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'evaluation_item_id',
        'solution',
        'status',
    ];
}
