<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'active'
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function activeCategoryCount()
    {
        return DB::select(
            'select count(0) as count from product_categories where active = 1'
        )[0]->count;
    }
}
