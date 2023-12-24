<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Theme;

class EvaluationSource extends Model
{
    use HasFactory;
    protected $table = 'evaluation_sources';
    protected $fillable = [
        'name',
        'title',
        'content',
        'author',
        'text_sourse',        
        'theme_id',
        'status'
    ];

    protected $with = ['theme'];
    public function theme() {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }
}
