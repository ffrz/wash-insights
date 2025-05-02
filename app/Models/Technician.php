<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'user_id', 'name', 'phone', 'address', 'email', 'active'
    ];

    /**
     * Get the company that owns the technician.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function activeTechnicianCount()
    {
        return DB::select(
            'select count(0) as count from technicians where active=1 and company_id=?',
            [Auth::user()->company_id]
        )[0]->count;
    }
}
