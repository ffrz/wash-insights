<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_datetime = current_datetime();
            $model->created_by_uid = Auth::id();
            return true;
        });

        static::updating(function ($model) {
            $model->updated_datetime = current_datetime();
            $model->updated_by_uid = Auth::id();
            return true;
        });
    }
}
