<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evaluation;

class EvaluationSubject extends Model
{
    use HasFactory;
    protected $table = 'evaluation_subjects';
    protected $fillable = [
        'name',
        'order_number',
        'path',
        'title',
        'evaluation_id',
        'status'
    ];

    protected $with = ['evaluation'];
    public function evaluation() {
        return $this->belongsTo(Evaluation::class, 'evaluation_id', 'id');
    }
}
