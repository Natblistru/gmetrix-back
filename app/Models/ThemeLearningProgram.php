<?php

namespace App\Models;

use App\Models\Theme;
use App\Models\LearningProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $with = ['learning_program', 'theme'];
    public function learning_program() {
        return $this->belongsTo(LearningProgram::class, 'learning_program_id', 'id');
    }

    public function theme() {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }



}
