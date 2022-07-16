<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Models\Stock;
use App\Models\User;
use App\Models\Plan;
use App\Models\PriceType;
use App\Models\StockPriceType;
use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StocksController extends Controller
{
    // 一覧画面
    public function index($year = null, $month = null, $plan_id = null, $price_type_id = null)
    {
        $company_id = auth()->user()->company_id;
        $plans = Plan::where('company_id', $company_id)
        ->orderBy('sort', 'asc')
        ->where('sort', '!=', '')
        ->get();
        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }
        
        $dates = $this->getCalendarDates($year, $month);
        if (!$plan_id) {
            $plan = Plan::where('company_id', $company_id)
            ->orderBy('sort')
            ->where('sort', '!=', '')
            ->first();
            $plan_id = $plan->id;
        }
        $default_plan = Plan::where('id', $plan_id)->first();
        $priceTypes=PriceType::whereIn('number', 
               function ($query) use($plan_id)
               {
                   $query->select('type')
                         ->from('prices')
                         ->where('plan_id', $plan_id);
               })->get();
            //    echo '<pre>';
            //    print_r($priceTypes);die();
        if ($price_type_id ===null) {
        
            $price = Price::where('plan_id', $plan_id)
            ->orderBy('id')
            ->first();
            $priceType = PriceType::where('number', $price->type)->first();
            $price_type_id = $priceType->number;
        }
        $default_priceType = PriceType::where('number',$price_type_id)->first();
  
        if($default_priceType){
            $stocks = Stock::where('plan_id', $plan_id)->where('price_type_id', $default_priceType->number)->get();
        }else{
            $default_priceType = PriceType::where('id',$price_type_id)->first();
            $stocks = Stock::where('plan_id', $plan_id)->get();
        }
    
        // echo '<pre>';
        // print_r( $stocks);die();
        $tmp_priceType = PriceType::where('number', $price_type_id)->first();   
        $price = Price::where('plan_id', $plan_id)->where('type', $tmp_priceType->number)->first();

        return view('client.stocks.index', compact('dates', 'year', 'month', 'plans', 'default_plan', 'stocks', 'priceTypes', 'default_priceType', 'price'));
    }
    // 作成画面 
    public function create()
    {
        
    }
    // 作成処理
    public function store(Request $request)
    {
    }
    // 作成処理 users
    public function storeUser(Request $request)
    {
    }
    // 詳細画面
    public function show($id)
    {
    }
    // 編集画面
    public function edit($id)
    {
        $stocks = Stock::find($id);
        return view('client.edit', compact('stocks'));
    }
    // 更新処理
    public function update(Request $request, $id)
    {

        $plan = Plan::find($id);

        foreach($request->limit_number as $ln) {
            if ($ln > $plan->res_limit_number) {
                throw ValidationException::withMessages(['limit_number' => '上限値を超えた在庫数は設定できません']);
            }
        }
        $stocks = $plan->stocks;
        foreach ($request->days as $i => $day) {

            foreach ($stocks as $stock) {
                if ($day == $stock->res_date && $request->price_type_id == $stock->price_type_id) {
                    Stock::where('id', $stock->id)->update([
                        'is_active'     => $request->is_active[$i],
                        'rank'          => $request->rank[$i],
                        'limit_number'  => $request->limit_number[$i]
                    ]);
                }
            }
            $stock_result = Stock::where('res_date', $day)->where('plan_id', $plan->id)->get();
            if (count($stock_result) == 0){
                $new_stock = new Stock();
                $new_stock->plan_id = $plan->id;
                $new_stock->price_type_id = $request->price_type_id;
                $new_stock->res_date = $day;
                $new_stock->is_active = $request->is_active[$i];
                $new_stock->rank = $request->rank[$i];
                $new_stock->limit_number = $request->limit_number[$i];
                $new_stock->save();
            }
        }
        //値段
        $priceTypes = PriceType::whereIn('number', 
               function ($query) use($plan)
               {
                   $query->select('type')
                         ->from('prices')
                         ->where('plan_id', $plan->id);
               })->get();
        foreach ($request->days as $i => $day) {
            StockPriceType::where('plan_id', $plan->id)->where('res_date', $day)->delete();
            foreach ($priceTypes as $priceType) {

                $name = 'price_'.$day.'_'. $priceType->number;
                if($request->{$name}){

                    $stockPriceType = new StockPriceType();
                    $stockPriceType->price_type_number = $priceType->number;
                    $stockPriceType->res_date =$day;
                    $stockPriceType->price = $request->{$name};
                    $stockPriceType->price_type_id = $request->price_type_id;
                    $stockPriceType->plan_id = $plan->id;
                    $stockPriceType->save();

                }
               
            }
        }

        return redirect()->back()->with('message', '変更が完了しました');
    }




    // 更新処理
    public function updateRank(Request $request, $id)
    {


        
        $plan = Plan::find($id);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        Stock::where('plan_id',$id)
            ->whereDate('res_date','>=', $start_date)
            ->whereDate('res_date','<=', $end_date)
            // ->where('price_type_id',$request->price_type_id)
            ->update([
                'rank'=>$request->rank1,
            ]);


        return redirect()->back()->with('message', '変更が完了しました');
    }
    public function updateStock(Request $request, $id)
    {


        
        $plan = Plan::find($id);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        Stock::where('plan_id',$id)
            ->whereDate('res_date','>=', $start_date)
            ->whereDate('res_date','<=', $end_date)
            // ->where('price_type_id',$request->price_type_id)
            ->update([
                'limit_number' => $request->limit_num
            ]);


        return redirect()->back()->with('message', '変更が完了しました');
    }
    // 更新処理
    public function updateRank_day(Request $request, $id)
    {
        $plan = Plan::find($id);

        $week_data = $request->week;
        $explode_data = explode(',', $week_data);
        $i = 0;
        $indexs = [];
        $j = 0;
        foreach ($explode_data as $explo) {
            $i++;
            if($explo == '1'){
                $indexs[$j++] = $i;
            }

        }

        

        $year = $request->year;
        $month = $request->month;
    
        $dates = $this->getCalendarDates($year, $month);

        $start_date = [];
        for($p = 1 ; $p < count($dates) + 1; $p++){
            foreach ($indexs as $index) {
                # code...
                if($p % 7 == $index || $p % 7 == 0){
                    $start_date = explode(' ', $dates[$p-1])[0];
                    $data = Stock::where('plan_id',$id)
                            ->whereDate('res_date', $start_date)
                            // ->where('price_type_id',$request->price_type_id)
                            ->update([
                                'rank' => $request->rank2,
                            ]);
                }


            }
            
        }

        return redirect()->back()->with('message', '変更が完了しました');
    }
    // 更新処理
    public function updateStock_day(Request $request, $id)
    {
        $plan = Plan::find($id);

        $week_data = $request->week;
        $explode_data = explode(',', $week_data);
        $i = 0;
        $indexs = [];
        $j = 0;
        foreach ($explode_data as $explo) {
            $i++;
            if($explo == '1'){
                $indexs[$j++] = $i;
            }

        }

        $year = $request->year;
        $month = $request->month;
    
        $dates = $this->getCalendarDates($year, $month);

        $start_date = [];
        for($p = 1 ; $p < count($dates) + 1; $p++){
            foreach ($indexs as $index) {
                # code...
                if($p % 7 == $index || $p % 7 == 0){
                    $start_date = explode(' ', $dates[$p-1])[0];
                    $data = Stock::where('plan_id',$id)
                            ->whereDate('res_date', $start_date)
                            // ->where('price_type_id',$request->price_type_id)
                            ->update([
                                'limit_number' => $request->limit_num2
                            ]);
                }


            }
            
        }

        return redirect()->back()->with('message', '変更が完了しました');
    }


    // 複製処理
    public function replicate($id)
    {
        $oldrow = Stock::find($id);
        $newrow = $oldrow->replicate();
        $newrow->save();
        return redirect()->back();
    }

    // 削除処理
    public function destroy($id)
    {
        $stocks = Stock::destroy($id);
        return redirect()->back();
    }
    // 削除処理 users
    public function destroyUser($id)
    {
        $users = User::destroy($id);
        return redirect()->back();
    }

    // 選択削除処理
    public function destroySelected(Request $request)
    {
        $ids = explode(',', $request->ids);
        $stocks = Stock::destroy($ids);
        return redirect()->back();
    }
    // 選択削除処理 users
    public function destroySelectedUser(Request $request)
    {
        $ids = explode(',', $request->ids);
        $users = User::destroy($ids);
        return redirect()->back();
    }

    // JSON返却
    public function json()
    {
        try {
           $result = Stock::all();
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

    // JSON返却 users
    public function jsonUser()
    {
        try {
           $result = User::all();
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

    // 絞り込みJSON返却
    public function jsonSpecific($category)
    {
        try {
           $result = Stock::select()
               ->where('category', $category)
               ->get();
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

    // API
    public function indexJson($year = null, $month = null, $plan_id = null, $price_type_id = null)
    {
//        $company_id = auth()->user()->company_id;
        $company_id = 1;
        $plans = Plan::where('company_id', $company_id)
        ->orderBy('sort', 'asc')
        ->where('sort', '!=', '')
        ->get();
        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }
        $dates = $this->getCalendarDates($year, $month);
        if (!$plan_id) {
            $plan = Plan::where('company_id', $company_id)
            ->orderBy('sort')
            ->where('sort', '!=', '')
            ->first();
            $plan_id = $plan->id;
        }
        $default_plan = Plan::where('id', $plan_id)->first();
        $stocks = Stock::where('plan_id', $plan_id)->where('price_type_id', $price_type_id)->get();
        $priceTypes=PriceType::whereIn('number', 
               function ($query) use($plan_id)
               {
                   $query->select('type')
                         ->from('prices')
                         ->where('plan_id', $plan_id);
               })->get();
        foreach ($stocks as $key1 => $stock) {
            $arr=[];
            foreach ($priceTypes as $key2 => $priceType) {
                $stock_price_type =  StockPriceType::where('price_type_number',$priceType->number)->where('res_date',$stock->res_date)->where('plan_id',$plan_id)->first();
                if($stock_price_type){
                    $arr[$priceType->name]=$stock_price_type->price;
                }
            }

            $stock->price=$arr;
        }

        $array_data = [];
        $array_data['dates'] = $dates;
        $array_data['stocks'] = $stocks;
        return $array_data;
    }
    // カレンダー表示
    public function getCalendarDates($year, $month)
    {
        $dateStr = sprintf('%04d-%02d-01', $year, $month);
        $date = new Carbon($dateStr);
        $days = $date->daysInMonth; // 月に何日あるか取得
        $daysParWeek = $date::DAYS_PER_WEEK; // 1週の日数を取得(デフォルトは 7 が設定されている)

        $dayOfWeek = $date->startOfMonth()->dayOfWeek; // 1日の曜日(int)を取得


        $last_week =  (int) ceil(($days - ($daysParWeek - $dayOfWeek)) / $daysParWeek) + 1; // 最終日が何週目か計算


        $date->subDay($date->dayOfWeek); // 1日より前の日で埋める


        $count = 0;
        if ($last_week == 5) {
            $count = 35; //5週目で終わりの月は35マス分を埋めて終わり
        } else {
            $count = 42; //6週目で終わりの月は42マス分を埋めて終わり
        }
        $dates = [];
        for ($i = 0; $i < $count; $i++, $date->addDay()) {
            $dates[] = $date->copy();
        }
        return $dates;
    }
}
