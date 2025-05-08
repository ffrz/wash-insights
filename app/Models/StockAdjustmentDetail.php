<?php

namespace App\Models;

class StockAdjustmentDetail extends Model
{
    protected $fillable = [
        'parent_id',
        'product_id',
        'product_name',
        'quantity',
        'uom',
        'cost',
        'price',
        'subtotal_cost',
        'subtotal_price',
        'notes',
    ];

    public function parent()
    {
        return $this->belongsTo(StockAdjustment::class, 'parent_id');
    }
}
