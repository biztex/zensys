<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'limit_number',
        'is_active',
        'rank',
        'price',
        'stocks_id',
    ];

    public function Stocks()
    {
        return $this->belongsTo('App\Models\Stock');
    }

}
