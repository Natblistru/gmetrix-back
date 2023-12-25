<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\EvaluationItem;

class EvaluationAnswer extends Model
{
    use HasFactory;
    protected $table = 'evaluation_answers';
    protected $fillable = [
        'task',
        'content',
        'max_points',
        'evaluation_item_id',
        'order_number',
        'status'
    ];

    protected $with = ['evaluation_item'];
    public function evaluation_item() {
        return $this->belongsTo(EvaluationItem::class, 'evaluation_item_id', 'id');
    }
}
