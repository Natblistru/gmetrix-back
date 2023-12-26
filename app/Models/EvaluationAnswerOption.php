<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationOption;

class EvaluationAnswerOption extends Model
{
    use HasFactory;
    protected $table = 'evaluation_answer_options';
    protected $fillable = [
        'evaluation_answer_id',
        'evaluation_option_id',
        'status'
    ];

    protected $with = ['evaluation_answer', 'evaluation_option'];
    public function evaluation_answer() {
        return $this->belongsTo(EvaluationAnswer::class, 'evaluation_answer_id', 'id');
    }

    public function evaluation_option() {
        return $this->belongsTo(EvaluationOption::class, 'evaluation_option_id', 'id');
    }
}
