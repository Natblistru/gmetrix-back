<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSummativeTest extends Model
{
    use HasFactory;

    protected $fillable = [ // Coloanele care pot fi completate
        'student_id',
        'summative_test_id',
        'time',
        'score',
        'status',
    ];

}
