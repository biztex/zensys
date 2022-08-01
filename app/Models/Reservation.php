<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_name_first',
        'add_name_last',
        'add_kana_first',
        'add_kana_last',
        'add_age',
        'add_birth',
        'add_postalcode',
        'add_prefecture',
        'add_address',
        'add_building',
        'add_telephone',
        'add_boarding',
        'add_drop',
        'companion_name_first',
        'companion_name_last',
        'companion_kana_first',
        'companion_kana_last',
        'companion_age',
        'companion_birth',
        'companion_gender',
        'companion_boarding',
        'companion_drop',
    ];

    public function plan()
    {
        return $this->belongsTo('App\Models\Plan');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
