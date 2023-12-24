<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EvaluationSubject;
use App\Models\EvaluationSource;

class EvaluationSubjectSource extends Model
{
    use HasFactory;
    protected $table = 'evaluation_subject_sources';
    protected $fillable = [
        'name',
        'evaluation_subject_id',
        'evaluation_source_id',
        'order_number',
        'status'
    ];

    protected $with = ['evaluation_subject', 'evaluation_source'];
    public function evaluation_subject() {
        return $this->belongsTo(EvaluationSubject::class, 'evaluation_subject_id', 'id');
    }

    public function evaluation_source() {
        return $this->belongsTo(EvaluationSource::class, 'evaluation_source_id', 'id');
    }
}
