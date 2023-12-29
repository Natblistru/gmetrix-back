<?php

namespace App\Models;

use App\Models\FormativeTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormativeTestItem extends Model
{
    use HasFactory;
    protected $table = 'formative_test_items';
    protected $fillable = [
        'order_number',
        'formative_test_id',
        'test_item_id',
        'status'
    ];

    protected $with = ['test_item', 'formative_test' ];
    public function test_item() {
        return $this->belongsTo(TestItem::class, 'test_item_id', 'id');
    }
    public function formative_test() {
        return $this->belongsTo(FormativeTest::class, 'formative_test_id', 'id');
    }

}
