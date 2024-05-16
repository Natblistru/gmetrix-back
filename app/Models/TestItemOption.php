<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestItemOption extends Model
{
    use HasFactory;
    protected $table = 'test_item_options';
    protected $fillable = [
        'option',
        'option_ro',
        'explanation',
        'explanation_ro',
        'text_additional',
        'correct',
        'test_item_id',
        'status'
    ];

    protected $with = ['test_item'];
    public function test_item() {
        return $this->belongsTo(TestItem::class, 'test_item_id', 'id');
    }
}
