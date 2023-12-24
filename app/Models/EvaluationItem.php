<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EvaluationSubject;
use App\Models\Theme;

class EvaluationItem extends Model
{
    use HasFactory;
    protected $table = 'evaluation_items';
    protected $fillable = [
        'task',
        'statement',
        'image_path',
        'procent_paper',
        'editable_image_path',
        'nota',
        'theme_id',
        'evaluation_subject_id',
        'order_number',
        'status'
    ];

    protected $with = ['evaluation_subject', 'theme'];
    public function evaluation_subject() {
        return $this->belongsTo(EvaluationSubject::class, 'evaluation_subject_id', 'id');
    }

    public function theme() {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }
}
