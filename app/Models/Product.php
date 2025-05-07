<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'category_id',
        'supplier_id',
        'name',
        'barcode',
        'description',
        'active',
        'type',
        'cost',
        'price',
        'uom',
        'stock',
        'min_stock',
        'max_stock',
        'notes',
    ];

    /**
     * ServiceStatuses
     */
    const Type_Stocked = 'stocked';
    const Type_NonStocked = 'nonstocked';
    const Type_Service = 'service';

    const Types = [
        self::Type_Stocked => 'Stok',
        self::Type_NonStocked => 'Non Stok',
        self::Type_Service => 'Servis',
    ];

    /**
     * Get the category for the product.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Get the supplier for the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_uid');
    }

    public static function activeProductCount()
    {
        return DB::select(
            'select count(0) as count from products where active = 1'
        )[0]->count;
    }
}
