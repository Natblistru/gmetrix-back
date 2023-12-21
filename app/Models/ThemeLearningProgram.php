<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LearningProgram;

class ThemeLearningProgram extends Model
{
    use HasFactory;
    protected $table = 'theme_learning_programs';
    protected $fillable = [
        'id',
        'name',
        'learning_program_id',
        'theme_id',
        'status'
    ];

    // protected $with = ['learning_program'];
    // public function learning_program() {
    //     return $this->belongsTo(LearningProgram::class, 'learning_program_id', 'id');
    // }



}
