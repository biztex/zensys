<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPriceType extends Model
{
    use HasFactory;

    public function price_types()
    {
        return $this->hasOne('App\Models\PriceType', 'number', 'price_type_number');
    }
}
