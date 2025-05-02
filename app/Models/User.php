<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public const Role_Admin = 'admin';
    public const Role_Technician = 'technician';
    public const Role_Cashier = 'cashier';

    // Display role di hardcode saja, tidak diambil dari translations
    public const Roles = [
        self::Role_Admin => 'Administrator',
        self::Role_Technician => 'Teknisi',
        self::Role_Cashier => 'Kasir',
    ];

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'username',
        'email',
        'active',
        'password',
        'role',
        'last_login_datetime',
        'last_activity_description',
        'last_activity_datetime'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['company_name'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getCompanyNameAttribute()
    {
        return $this->company->name ?? 'No Company';
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function setLastLogin()
    {
        $this->last_login_datetime = now();
        $this->save();
    }

    public function setLastActivity($description)
    {
        $this->last_activity_description = $description;
        $this->last_activity_datetime = now();
        $this->save();
    }
}
