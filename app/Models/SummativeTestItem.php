<?php

namespace App\Models;

use App\Models\SummativeTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SummativeTestItem extends Model
{
    use HasFactory;
    protected $table = 'summative_test_items';
    protected $fillable = [
        'order_number',
        'summative_test_id',
        'test_item_id',
        'status'
    ];

    protected $with = ['test_item', 'summative_test' ];
    public function test_item() {
        return $this->belongsTo(TestItem::class, 'test_item_id', 'id');
    }
    public function summative_test() {
        return $this->belongsTo(SummativeTest::class, 'summative_test_id', 'id');
    }
}
