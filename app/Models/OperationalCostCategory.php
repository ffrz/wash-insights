<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationalCostCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'notes'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
