<?php

namespace App\Models;

class StockMovement extends Model
{
    protected $fillable = [
        'ref_id',
        'ref_type',
        'ref_datail_id',
        'ref_datail_type',
        'quantity',
        'uom',
        'cost',
        'price',
        'subtotal_cost',
        'subtotal_price',
        'notes',
        'created_datetime',
        'created_by_uid',
    ];

    // Define any relationships (example, adjust based on your use case)
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }

    // Define the ref relationship dynamically based on ref_type and ref_id
    public function ref()
    {
        // Check ref_type and return the corresponding related model
        switch ($this->ref_type) {
            case 'sales_order':
                return $this->belongsTo(SalesOrder::class, 'ref_id');
            case 'sales_order_return':
                return $this->belongsTo(SalesOrderReturn::class, 'ref_id');
            case 'purchase_order':
                return $this->belongsTo(PurchaseOrder::class, 'ref_id');
            case 'purchase_order_return':
                return $this->belongsTo(PurchaseOrderReturn::class, 'ref_id');
            case 'stock_adjustment':
                return $this->belongsTo(StockAdjustment::class, 'ref_id');
            default:
                return null;
        }
    }

        // Define the ref relationship dynamically based on ref_type and ref_id
        public function refDetail()
        {
            // Check ref_type and return the corresponding related model
            switch ($this->ref_type) {
                case 'sales_order_detail':
                    return $this->belongsTo(SalesOrderDetail::class, 'ref_detail_id');
                case 'sales_order_return_detail':
                    return $this->belongsTo(SalesOrderReturnDetail::class, 'ref_detail_id');
                case 'purchase_order_detail':
                    return $this->belongsTo(PurchaseOrderDetail::class, 'ref_detail_id');
                case 'purchase_order_return_detail':
                    return $this->belongsTo(PurchaseOrderReturnDetail::class, 'ref_detail_id');
                case 'stock_adjustment_detail':
                    return $this->belongsTo(StockAdjustmentDetail::class, 'ref_detail_id');
                default:
                    return null;
            }
        }
}
