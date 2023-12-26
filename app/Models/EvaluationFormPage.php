<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\EvaluationItem;

class EvaluationFormPage extends Model
{
    use HasFactory;
    protected $table = 'evaluation_form_pages';
    protected $fillable = [
        'task',
        'hint',
        'evaluation_item_id',
        'order_number',
        'status'
    ];

    protected $with = ['evaluation_item'];
    public function evaluation_item() {
        return $this->belongsTo(EvaluationItem::class, 'evaluation_item_id', 'id');
    }
}
