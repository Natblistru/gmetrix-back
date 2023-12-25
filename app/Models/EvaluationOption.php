<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationOption extends Model
{
    use HasFactory;
    protected $table = 'evaluation_options';
    protected $fillable = [
        'label',
        'points',
        'status'
    ];
}
