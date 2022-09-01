<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Models\Price;
use Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PriceController extends Controller
{
    
    // JSON返却
    public function json($plan_id)
    {
        try {
           $result = Price::select()->join('price_types', 'prices.type', '=', 'price_types.number')->where('plan_id',$plan_id)->orderBy('price_types.id')->get();
        } catch(\Exception $e){
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }
     // 上記メソッド内ファンクション
     private function resConversionJson($result, $statusCode=200)
     {
         if(empty($statusCode) || $statusCode < 100 || $statusCode >= 600){
             $statusCode = 500;
         }
         return response()->json($result, $statusCode, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
     }
}

