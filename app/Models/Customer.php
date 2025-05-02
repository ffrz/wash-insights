<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'address', 'active'
    ];

    /**
     * Get the service orders for the customer.
     */
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public static function activeCustomerCount()
    {
        return DB::select(
            'select count(0) as count from customers where active = 1'
        )[0]->count;
    }
}
