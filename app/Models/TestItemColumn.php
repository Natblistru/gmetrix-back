<?php

namespace App\Models;

use App\Models\TestItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestItemColumn extends Model
{
    use HasFactory;
    protected $table = 'test_item_columns';
    protected $fillable = [
        'order_number',
        'title',
        'test_item_id',
        'status'
    ];

    protected $with = ['test_item'];
    public function test_item() {
        return $this->belongsTo(TestItem::class, 'test_item_id', 'id');
    }
}
