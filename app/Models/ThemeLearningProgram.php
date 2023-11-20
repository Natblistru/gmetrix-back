<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeLearningProgram extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'learning_program_id',
        'theme_id',
    ];
}
