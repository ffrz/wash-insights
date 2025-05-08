<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'type',
        'total_cost',
        'total_price',
        'notes',
    ];

    // === Type ===
    public const Type_StockOpname     = 'stock_opname';
    public const Type_StockCorrection  = 'stock_correction';

    public const Types = [
        self::Type_StockOpname     => 'Stok Opname',
        self::Type_StockCorrection  => 'Koreksi Stok',
    ];

    // === Status ===
    public const Status_Draft     = 'draft';
    public const Status_Closed    = 'closed';
    public const Status_Cancelled = 'cancelled';

    public const Statuses = [
        self::Status_Draft     => 'Draft',
        self::Status_Closed    => 'Selesai',
        self::Status_Cancelled => 'Dibatalkan',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_uid');
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'parent_id');
    }
}
