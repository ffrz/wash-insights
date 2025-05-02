<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'phone', 'address', 'email', 'active'
    ];

    /**
     * Get the company that owns the customer.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

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
            'select count(0) as count from customers where active = 1 and company_id=?',
            [Auth::user()->company_id]
        )[0]->count;
    }
}
