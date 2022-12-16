<?php

namespace App\Http\Controllers;

use App\Constants\PaymentMethodConstants;
use App\Helpers;
use App\Models\Bankaccount;
use App\Models\Company;
use App\Models\CreditCancel;
use App\Models\Genre;
use App\Models\Plan;
use App\Models\Price;
use App\Models\PriceType;
use App\Models\Reservation;
use App\Models\Sequence;
use App\Models\Stock;
use App\Models\StockPriceType;
use App\Models\User;
use App\Rules\ZipcodeRule;
use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use LogicException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use tgMdk\dto\CardCancelRequestDto;
use tgMdk\dto\CardCancelResponseDto;
use tgMdk\dto\CardReAuthorizeRequestDto;
use tgMdk\dto\CardReAuthorizeResponseDto;
use tgMdk\dto\CvsCancelRequestDto;
use tgMdk\dto\CvsCancelResponseDto;
use tgMdk\TGMDK_Config;
use tgMdk\TGMDK_Logger;
use tgMdk\TGMDK_Transaction;
use Yasumi\Yasumi;

class ReservationsController extends Controller
{
    // 一覧画面
    public function index()
    {
        return view('client.reservations.index');
    }
    // 作成画面
    public function create()
    {
        $reservations = Reservation::all();
        $categories = Genre::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderBy('id')
            ->get();
        $genres = Genre::all();
        return view(
            'client.reservations.create',
            compact('reservations', 'categories', 'genres')
        );
    }
    // 作成画面 for user
    public function createForUser(Request $request)
    {
        $data = $request->only(['plan_id', 'date','price_type_id']);
        $date = $data['date'];
        $dt = new Carbon($date);
        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];
        // 祝日判定
        $holidays = Yasumi::create('Japan', $dt->format('Y'));
        foreach ($holidays->getHolidayDates() as $holiday) {
            if ($holiday == $dt->format('Y-m-d')) {
                $weekday = 'holiday';
            }
        }
        $plan = Plan::select()
            ->where('id', $data['plan_id'])
            ->with('activities')
            ->with('prices')
            ->first();

            if($plan->boarding != null){
                $pieces = explode(',',$plan->boarding);
            }
            else{
                $pieces = null;
            }
            if($plan->drop != null){
                $drops = explode(',',$plan->drop);
            }
            else{
                $drops = null;
            }

        /////////
        $stock = Stock::select()
            ->where('plan_id', $data['plan_id'])
            ->where('res_date', date('Y-m-d', strtotime($data['date'])))
            ->first();

        $companies =  Company::all();

        $priceType = PriceType::select()
            ->where('number' ,$data['price_type_id'])
            ->first();



        $stock_price_types = Price::select()
                ->where('plan_id', $data['plan_id'])
                ->where('type',$data['price_type_id'])
                ->get();


        return view(
            'user.reservations.create',
            compact('plan','pieces','drops' ,'companies', 'date', 'weekday', 'stock','priceType', 'stock_price_types')
        );
    }
    // 作成処理
    public function storeForUser(Request $request)
    {
        $rules = [
            'plan_id' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'name_last' => ['required', 'string', 'max:50'],
            'name_first' => ['required', 'string', 'max:50'],
            'kana_last' => ['required', 'regex:/^[ァ-ヾ　〜ー]+$/u', 'max:50'],
            'kana_first' => ['required', 'regex:/^[ァ-ヾ　〜ー]+$/u', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'postalcode' => ['required', 'string', 'max:8'],
            'companion_postalCode.*' => ['required', 'string', 'max:8', new ZipcodeRule],
            'prefecture' => ['required', 'string', 'max:5'],
            'tel' => ['required', 'string', 'max:50'],
            'birth_year' => ['required', 'numeric'],
            'birth_month' => ['required', 'numeric'],
            'birth_day' => ['required', 'numeric'],
            'tel2' => ['required', 'string', 'max:50'],
            'is_member' => ['nullable'],
            'is_request' => ['nullable'],
        ];

        $plan = Plan::find($request->plan_id);
        if ($plan->answer_flag == 1) {
            $a_rules = [
                'answer.*' => ['required', 'string', 'max:500'],
            ];
            $rules = array_merge($rules, $a_rules);
        }

        $count_member = 0;
        // for ($i = 0; $i <= 20; $i++) {
        //     $p_rules = [];
        //     $count = $request->{'type' . $i . '_number'};
        //     if ($count > 0) {
        //         $p_rules = [
        //             'type' . $i . '_number' => [
        //                 'required',
        //                 'numeric',
        //                 ' min:0',
        //                 'max:99',
        //             ],
        //         ];
        //         $count_member += $count;
        //     }
        //     $rules = array_merge($rules, $p_rules);
        // }
        // 在庫をチェック
        $stock_before_minus = Stock::select()
            ->where('plan_id', $request->plan_id)
            ->where('res_date', date('Y-m-d', strtotime($request->date)))
            ->first();
        $stock_after_minus = $stock_before_minus->limit_number - $count_member;
        if ($request->is_request == 0 && $stock_after_minus < 0) {
            throw ValidationException::withMessages([
                'stock_is_out' =>
                    '現在予約在庫がありません。お手数ですが実施会社までお問い合わせください',
            ]);
        }
        // 参加可能人数をチェック
        // if ($count_member == 0) {
        //     throw ValidationException::withMessages([
        //         'count_member' => '参加人数は最低1名以上必要です',
        //     ]);
        // } elseif ($count_member < $plan->min_number) {
        //     throw ValidationException::withMessages([
        //         'min_member' =>
        //             'このプランの最低参加人数は' .
        //             $plan->min_number .
        //             '名以上です',
        //     ]);
        // } elseif ($count_member > $plan->max_number) {
        //     throw ValidationException::withMessages([
        //         'max_member' =>
        //             'このプランの最大参加人数は' .
        //             $plan->max_number .
        //             '名までです',
        //     ]);
        // }
        $this->validate($request, $rules);
        // 会員追加
        //if ($request->is_member == 1) {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = new User();
            $user->name_last = $request->name_last;
            $user->name_first = $request->name_first;
            $user->kana_last = $request->kana_last;
            $user->kana_first = $request->kana_first;
            $user->tel = $request->tel;
            $user->tel2 = $request->tel2;
            $user->email = $request->email;
            $user->birth_day =
                $request->birth_year .
                '-' .
                $request->birth_month .
                '-' .
                $request->birth_day;
            $user->postal_code = $request->postalcode;
            $user->prefecture = $request->prefecture;
            $user->address = $request->address;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            } else {
                $user->password = Hash::make($request->tel);
            }
            $user->is_member = $request->is_member;
            $user->save();
        } else {
            $user->name_last = $request->name_last;
            $user->name_first = $request->name_first;
            $user->kana_last = $request->kana_last;
            $user->kana_first = $request->kana_first;
            $user->tel = $request->tel;
            $user->tel2 = $request->tel2;
            $user->email = $request->email;
            $user->birth_day =
                $request->birth_year .
                '-' .
                $request->birth_month .
                '-' .
                $request->birth_day;
            $user->postal_code = $request->postalcode;
            $user->prefecture = $request->prefecture;
            $user->address = $request->address;
            $user->is_member = $request->is_member;
            $user->save();
        }

        //}
        // 予約番号作成
        $reservation_number = $this->getNewOrderIdByDate(Carbon::today());
        // 予約追加
        $reservation = new Reservation();
        $reservation->plan_id = $request->plan_id;
        $reservation->user_id = $user->id;
        $reservation->order_id = $reservation_number;
        $reservation->number = $reservation_number;
        $reservation->activity_date = $request->selected_activity;
        $reservation->fixed_datetime = $request->date;
        $reservation->answer = json_encode($request->answer);
        $reservation->remark = $request->remark;
        $reservation->price_type = $request->price_type;


        $reservation->add_name_first = $request->add_firstname[0];
        $reservation->add_name_last = $request->add_lastname[0];
        $reservation->add_kana_first = $request->join_kana2[0];
        $reservation->add_kana_last = $request->join_kana1[0];
        $reservation->add_age = $request->join_age[0];
        $reservation->add_birth = $request->birth_year_representative[0] .'-'.  $request->birth_month_representative[0].'-' . $request->birth_day_representative[0];
        $reservation->add_postalcode = $request->postalcode_representative;
        $reservation->add_prefecture = $request->prefecture_representative;
        $reservation->add_address = $request->address_representative;
        $reservation->add_telephone = $request->tel_representative;
        $reservation->add_building =    $request->extended_representative;

        if(isset($request->boarding)){
            $reservation->add_boarding = $request->boarding[0];
        }
        if(isset($request->add_drop)){
            $reservation->add_drop = $request->drop[0];
        }

        // add_age、全角スペース除去
        $reservation->add_age = preg_replace('/\A[\x00\s]++|[\x00\s]++\z/u', '', $reservation->add_age);
        // 数値型以外混在の場合、誕生日から年齢を算出し、再設定
        if(!is_int($reservation->add_age) | $reservation->add_age < 0){
            $birth_day = new Carbon(now()->format('Y').'/'.$request->birth_month_representative[0].'/'.$request->birth_day_representative[0]);
            $reservation->add_age = now()->format('Y') - $request->birth_year_representative[0];
            if(now() < $birth_day){
                $reservation->add_age = $reservation->add_age -1;
            }
        }

        $temp_companion_name_first = [];
        $temp_companion_name_last = [];
        $temp_companion_kana_first = [];
        $temp_companion_kana_last = [];
        $temp_companion_age = [];
        $temp_companion_gender = [];
        $temp_companion_postalCode = [];
        $temp_companion_prefecture = [];
        $temp_companion_address = [];
        $temp_companion_extended = [];
        $temp_companion_birth = [];
        $temp_companion_boarding = [];
        $temp_companion_drop = [];

        for($i=1; $i<count($request->add_firstname); $i++){
            $temp_companion_name_first[$i-1]    = $request->add_firstname[$i];
            $temp_companion_name_last[$i-1]     = $request->add_lastname[$i];
            $temp_companion_kana_first[$i-1]    = $request->join_kana1[$i];
            $temp_companion_kana_last[$i-1]     = $request->join_kana2[$i];
            $temp_companion_age[$i-1]           = $request->join_age[$i];
            $temp_companion_gender[$i-1]        = $request->join_sex[$i];
            $temp_companion_postalCode[$i-1]    = $request->companion_postalCode[$i-1];
            $temp_companion_prefecture[$i-1]    = $request->companion_prefecture[$i-1];
            $temp_companion_address[$i-1]       = $request->companion_address[$i-1];
            $temp_companion_extended[$i-1]      = $request->companion_extended[$i-1];
            $temp_companion_birth[$i-1]         = $request->birth_year_representative[$i] .'-'.  $request->birth_month_representative[$i].'-' . $request->birth_day_representative[$i];
            if(isset($request->boarding[$i])){
                $temp_companion_boarding[$i-1] = $request->boarding[$i];
            }
            if(isset($request->drop[$i])){
                $temp_companion_drop[$i-1]  = $request->drop[0];
            }

        }



        $reservation->companion_name_first  = json_encode($temp_companion_name_first);
        $reservation->companion_name_last   = json_encode($temp_companion_name_last);
        $reservation->companion_kana_first  = json_encode($temp_companion_kana_first);
        $reservation->companion_kana_last   = json_encode($temp_companion_kana_last);
        $reservation->companion_age         = json_encode($temp_companion_age);
        $reservation->companion_gender      = json_encode($temp_companion_gender);
        $reservation->companion_postalCode  = json_encode($temp_companion_postalCode);
        $reservation->companion_prefecture  = json_encode($temp_companion_prefecture);
        $reservation->companion_address     = json_encode($temp_companion_address);
        $reservation->companion_extended    = json_encode($temp_companion_extended);
        $reservation->companion_birth       = json_encode($temp_companion_birth);
        $reservation->companion_boarding    = json_encode($temp_companion_boarding);
        $reservation->companion_drop        = json_encode($temp_companion_drop);



        $reservation->type0_number = $request->type0;
        $reservation->type1_number = $request->type1;
        $reservation->type2_number = $request->type2;
        $reservation->type3_number = $request->type3;
        $reservation->type4_number = $request->type4;
        $reservation->type5_number = $request->type5;
        $reservation->type6_number = $request->type6;
        $reservation->type7_number = $request->type7;
        $reservation->type8_number = $request->type8;
        $reservation->type9_number = $request->type9;
        $reservation->type10_number = $request->type10;
        $reservation->type11_number = $request->type11;
        $reservation->type12_number = $request->type12;
        $reservation->type13_number = $request->type13;
        $reservation->type14_number = $request->type14;
        $reservation->type15_number = $request->type15;
        $reservation->type16_number = $request->type16;
        $reservation->type17_number = $request->type17;
        $reservation->type18_number = $request->type18;
        $reservation->type19_number = $request->type19;
        $reservation->type20_number = $request->type20;
        $reservation->payment_method = $request->payment_method;


        $prices = Price::select()
                    ->where('plan_id' , $request->plan_id)
                    ->where('type' , $request->price_type)
                    ->get();
        $priceName = PriceType::select()
                        ->where('number' , $request->price_type)
                        ->first();
        if ($request->is_request == 0) {
            $reservation->status = '未決済';
        } else {
            $reservation->status = 'リクエスト予約';
        }
        // 料金区分２０以上対応
        $Number_of_reservations = [];
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        for($i=0;$i<=100;$i++){
            for($j=0;$j<count($byDay);$j++){
                for($k=1;$k<=3;$k++){
                    if(!is_null($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)})){
                        if($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} > 0){
                            $typeid = $i;
                            $pType = $byDay[$j];
                            $prtype = $j;
                            $Number_of_reservations[sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)] = $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }
        }
        $type_id = $typeid;
        $prices = Price::select()
                    ->where('plan_id' , $request->plan_id)
                    ->where('type' , $typeid)
                    ->get();
        $priceName = PriceType::select()
                        ->where('number' , $typeid)
                        ->first();
        $Number_of_reservations = array_merge($Number_of_reservations);
        $reservation->Number_of_reservations = json_encode($Number_of_reservations);

        $reservation->save();
        //$orderId = Helpers::generateOrderId();
        // 合計金額セット
        $amount = 0;
        $dt = new Carbon($request->date);
        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];
        // 祝日判定
        $holidays = Yasumi::create('Japan', $dt->format('Y'));
        foreach ($holidays->getHolidayDates() as $holiday) {
            if ($holiday == $dt->format('Y-m-d')) {
                $weekday = 'holiday';
            }
        }
        if ($stock_before_minus->price) {
            $amount = $stock_before_minus->price;
        } else {
            // 料金区分２０以上対応
            // 合計金額リセット
            $amount = 0;
            // 予約人数リセット
            $count_member = 0;
            foreach ($plan->prices as $price) {
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(!is_null($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)})){
                                if($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} > 0 && $price->type == $i){
                                    $amount += $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} * $price->{sprintf('%s_%d', $byDay[$j],$k)};
                                    $count_member += $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                                }
                            }
                        }
                    }
                }
            }
        }

        $tokenApiKey = config('setting.mdk.token_api_key');
        $orderId = $reservation_number;

        $req_result = $request->is_request;

        // ここまで
        if ($request->is_request == 0) {
            if ($reservation->payment_method == PaymentMethodConstants::CARD) {
                $reservation->status = '決済処理中';
                $reservation->save();
                return view(
                    'card.index',
                    compact(
                        'reservation',
                        'amount',
                        'user',
                        'tokenApiKey',
                        'orderId',
                        'weekday',
                        'prices',
                        'priceName',
                        'type_id'
                    )
                );
            } elseif ($reservation->payment_method == PaymentMethodConstants::CVS) {
                $reservation->status = '決済処理中';
                $reservation->save();
                return view(
                    'cvs.index',
                    compact(
                        'reservation',
                        'amount',
                        'user',
                        'tokenApiKey',
                        'orderId',
                        'weekday',
                        'prices',
                        'priceName',
                        'type_id'
                    )
                );
            } elseif ($reservation->payment_method == PaymentMethodConstants::PREPAY) {
                // 事前支払い
                $bank = Bankaccount::find($reservation->plan->company->id);
                // 予約者へメール通知
                Mail::send(
                    ['text' => 'user.reservations.prepayemail'],
                    [
                        'number' => $reservation->order_id,
                        'plan' => $reservation->plan->name,
                        'date' => date(
                            'Y年m月d日',
                            strtotime($reservation->fixed_datetime)
                        ),
                        'activity' => $reservation->activity_date,
                        'name_last' => $request->name_last,
                        'name_first' => $request->name_first,
                        'email' => $request->email,
                        'tel' => $request->tel,
                        'tel2' => $request->tel2,
                        'kana_last' => $request->kana_last,
                        'kana_first' => $request->kana_first,
                        'postalcode' => $request->postalcode,
                        'prefecture' => $request->prefecture,
                        'address' => $request->address,
                        'birth_year' => $request->birth_year,
                        'birth_month' => $request->birth_month,
                        'birth_day' => $request->birth_day,
                        'reservation' => $reservation,
                        'payment' => '銀行振込',
                        'haraikomiUrl' => null,
                        'amount' => $amount,
                        'weekday' => $weekday,
                        'bank' => $bank,
                        'prices' => $prices,
                        'priceName' => $priceName,
                        'type_id' => $typeid
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->bcc([config('mail.custom.addresses.main')])
                                ->from(config('mail.custom.addresses.main'))
                                ->subject('【予約確定】長野電鉄株式会社');
                        }
                    }
                );
                // 決済ステータスをDBへ格納
                $reservation->status = '予約確定';
                $reservation->save();
                // 在庫を減数
                $stock = Stock::select()
                    ->where('plan_id', $reservation->plan->id)
                    ->where(
                        'res_date',
                        date('Y-m-d', strtotime($reservation->fixed_datetime))
                    )
                    ->first();
                if ($stock) {
                    if ($reservation->plan->res_limit_flag == 0) {
                        $stock->limit_number =
                            $stock->limit_number - $count_member;
                        $stock->save();
                    } else {
                        $stock->limit_number = $stock->limit_number - 1;
                        $stock->save();
                    }
                }
                return view('user.reservations.result' , compact('req_result'));
            } elseif ($reservation->payment_method == PaymentMethodConstants::SPOT) {
                // 現地支払い
                // 予約者へメール通知
                Mail::send(
                    ['text' => 'user.reservations.spotemail'],
                    [
                        'number' => $reservation->order_id,
                        'plan' => $reservation->plan->name,
                        'date' => date(
                            'Y年m月d日',
                            strtotime($reservation->fixed_datetime)
                        ),
                        'activity' => $reservation->activity_date,
                        'name_last' => $request->name_last,
                        'name_first' => $request->name_first,
                        'email' => $request->email,
                        'tel' => $request->tel,
                        'tel2' => $request->tel2,
                        'kana_last' => $request->kana_last,
                        'kana_first' => $request->kana_first,
                        'postalcode' => $request->postalcode,
                        'prefecture' => $request->prefecture,
                        'address' => $request->address,
                        'birth_year' => $request->birth_year,
                        'birth_month' => $request->birth_month,
                        'birth_day' => $request->birth_day,
                        'reservation' => $reservation,
                        'payment' => '現地払い',
                        'haraikomiUrl' => null,
                        'weekday' => $weekday,
                        'amount' => $amount,
                        'prices' => $prices,
                        'priceName' => $priceName,
                        'type_id' => $typeid
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->bcc([config('mail.custom.addresses.main')])
                                ->from(config('mail.custom.addresses.main'))
                                ->subject('【予約確定】長野電鉄株式会社');
                        }
                    }
                );
                // 決済ステータスをDBへ格納
                $reservation->status = '予約確定';
                $reservation->save();
                // 在庫を減数
                $stock = Stock::select()
                    ->where('plan_id', $reservation->plan->id)
                    ->where(
                        'res_date',
                        date('Y-m-d', strtotime($reservation->fixed_datetime))
                    )
                    ->first();
                if ($stock) {
                    if ($reservation->plan->res_limit_flag == 0) {
                        $stock->limit_number =
                            $stock->limit_number - $count_member;
                        $stock->save();
                    } else {
                        $stock->limit_number = $stock->limit_number - 1;
                        $stock->save();
                    }
                }
                return view('user.reservations.result' , compact('req_result'));
            }
        } else {
            // リクエスト予約者へメール通知
            Mail::send(
                ['text' => 'user.reservations.reqemail'],
                [
                    'number' => $reservation->order_id,
                    'plan' => $plan->name,
                    'date' => date('Y年m月d日', strtotime($request->date)),
                    'activity' => $request->selected_activity,
                    'name_last' => $request->name_last,
                    'name_first' => $request->name_first,
                    'email' => $request->email,
                    'tel' => $request->tel,
                    'tel2' => $request->tel2,
                    'kana_last' => $request->kana_last,
                    'kana_first' => $request->kana_first,
                    'postalcode' => $request->postalcode,
                    'prefecture' => $request->prefecture,
                    'address' => $request->address,
                    'birth_day' => $request->birth_day,
                    'birth_year' => $request->birth_year,
                    'birth_month' => $request->birth_month,
                    'reservation' => $reservation,
                    'weekday' => $weekday,
                    'amount' => $amount,
                    'prices' => $prices,
                    'priceName' => $priceName,
                    'type_id' => $typeid,
                    'pType' => $pType,
                ],
                function ($message) use ($request) {
                    if ($request->email) {
                        $message
                            ->to($request->email)
                            ->bcc([config('mail.custom.addresses.main')])
                            ->from(config('mail.custom.addresses.main'))
                            ->subject('【リクエスト】長野電鉄株式会社');
                    }
                }
            );
            return view('user.reservations.reqresult' , compact('req_result'));
        }
    }

    // 作成処理
    public function confirmForUser(Request $request)
    {

        $rules = [
            'plan_id' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'age' => ['required', 'numeric'],
            'name_last' => ['required', 'string', 'max:50'],
            'name_first' => ['required', 'string', 'max:50'],
            'kana_last' => ['required', 'regex:/^[ァ-ヾ　〜ー]+$/u', 'max:50'],
            'kana_first' => ['required', 'regex:/^[ァ-ヾ　〜ー]+$/u', 'max:50'],
            'email' => 'required|string|email|max:255|confirmed',
            'postalcode' => ['required', 'string', 'max:8', new ZipcodeRule],
            'postalcode_representative' => ['required', 'string', 'max:8', new ZipcodeRule],
            'companion_postalCode.*' => ['required', 'string', 'max:8', new ZipcodeRule],
            'prefecture' => ['required', 'string', 'max:5'],
            'tel' => ['required', 'string', 'max:50'],
            'birth_year' => ['required', 'numeric'],
            'birth_month' => ['required', 'numeric'],
            'birth_day' => ['required', 'numeric'],
            'tel2' => ['required', 'string', 'max:50'],
            'is_member' => ['nullable'],
            'is_request' => ['nullable'],
            'payment_method' => ['required', 'numeric'],
            'remark' => ['nullable', 'string','max:2000'],
        ];
        $plan = Plan::find($request->plan_id);
        if ($plan->answer_flag == 1) {
            $a_rules = [
                'answer.*' => ['required', 'string', 'max:500'],
            ];
            $rules = array_merge($rules, $a_rules);
        }

        $count_member = 0;

        // 在庫をチェック
        $stock_before_minus = Stock::select()
            ->where('plan_id', $request->plan_id)
            ->where('res_date', date('Y-m-d', strtotime($request->date)))
            ->first();
        $stock_after_minus = $stock_before_minus->limit_number - $count_member;
        if ($request->is_request == 0 && $stock_after_minus < 0) {
            throw ValidationException::withMessages([
                'stock_is_out' =>
                    '現在予約在庫がありません。お手数ですが実施会社までお問い合わせください',
            ]);
        }
        //参加可能人数をチェック

        $rank = ['a','b','c','d','e','f','g','h','i','j','k','l'];

        for($j=0; $j<count($rank); $j++){
            for ($i = 1; $i < 4; $i++) {
                $count = $request->{'type'.$request->price_type. '_'. $rank[$j].'_'. $i . '_number'};
                if ($count > 0) {
                    $count_member += $count;
                }
            }
        }

        $real_number = $request->limit_number;

        // if ($real_number != $count_member) {
        //     throw ValidationException::withMessages([
        //         'count_member' => '参加人数と予約数が一致しません',
        //     ]);
        // }
        if ($count_member == 0) {
            throw ValidationException::withMessages([
                'count_member' => '参加人数は最低1名以上必要です',
            ]);
        } elseif ($plan->min_number != null && $count_member < $plan->min_number) {
            throw ValidationException::withMessages([
                'min_member' =>
                    'このプランの最低参加人数は' .
                    $plan->min_number .
                    '名以上です',
            ]);
        } elseif ($plan->max_number != null && $count_member > $plan->max_number) {
            throw ValidationException::withMessages([
                'max_member' =>
                    'このプランの最大参加人数は' .
                    $plan->max_number .
                    '名までです',
            ]);
        }


        $this->validate($request, $rules);

        $companies = Company::all();
        $date = $request->date;
        $dt = new Carbon($date);
        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];

        $stock = Stock::select()
            ->where('plan_id', $request->plan_id)
            ->where('res_date', date('Y-m-d', strtotime($date)))
            ->first();
        $priceTypes = PriceType::whereIn('number', function ($query) use (
            $request
        ) {
            $query
                ->select('type')
                ->from('prices')
                ->where('plan_id', $request->plan_id);
        })->get();

        $arr = [];
        foreach ($priceTypes as $key2 => $priceType) {
            $stock_price_type = StockPriceType::where(
                'price_type_number',
                $priceType->number
            )
                ->where('res_date', $stock->res_date)
                ->where('plan_id', $request->plan_id)
                ->first();
            if ($stock_price_type) {
                $arr[] = $stock_price_type;
            }
        }

        $stock_price_types = $arr;


        // 料金
        $prices_name = $request->price_table_name ;
        $prices_types = $request->price_table_type ;
        $prices = $request->price_table_price ;
        // $types = [];
        // foreach ($request->type_number as $type) {
        //     # code...
        //     $types = $type;
        // }

        //


        $info = [];
        $info['name_last']                  = $request->name_last;
        $info['name_first']                 = $request->name_first;
        $info['kana_last']                  = $request->kana_last;
        $info['kana_first']                 = $request->kana_first;
        $info['radio_sex']                  = $request->radio_sex;
        $info['birth_year']                 = $request->birth_year;
        $info['birth_month']                = $request->birth_month;
        $info['birth_day']                  = $request->birth_day;
        $info['email']                      = $request->email;
        $info['postalcode']                 = $request->postalcode;
        $info['prefecture']                 = $request->prefecture;
        $info['address']                    = $request->address;
        $info['extended']                   = $request->extended;
        $info['tel']                        = $request->tel;
        $info['tel2']                       = $request->tel2;
        $info['add_lastname']               = $request->add_lastname;
        $info['add_firstname']              = $request->add_firstname;
        $info['join_kana1']                 = $request->join_kana1;
        $info['join_kana2']                 = $request->join_kana2;
        $info['join_age']                   = $request->join_age;
        $info['join_sex']                   = $request->join_sex;
        $info['companion_postalCode']       = $request->companion_postalCode;
        $info['companion_prefecture']       = $request->companion_prefecture;
        $info['companion_address']          = $request->companion_address;
        $info['companion_extended']         = $request->companion_extended;
        $info['birth_year_representative']  = $request->birth_year_representative;
        $info['birth_month_representative'] = $request->birth_month_representative;
        $info['birth_day_representative']   = $request->birth_day_representative;
        $info['postalcode_representative']  = $request->postalcode_representative;
        $info['prefecture_representative']  = $request->prefecture_representative;
        $info['address_representative']     = $request->address_representative;
        $info['extended_representative']    = $request->extended_representative;
        $info['tel_representative']         = $request->tel_representative;
        $info['payment_method']             = $request->payment_method;
        $info['address_representative']     = $request->address_representative;
        $info['boarding']                   = $request->boarding;
        $info['drop']                       = $request->drop;
        $info['answer']                     = json_encode($request->answer);
        $info['remark']                     = $request->remark;
        $info['price_type']                 = $request->price_type;
        // 料金区分２０以上対応
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        for($i=0;$i<=100;$i++){
            for($j=0;$j<count($byDay);$j++){
                for($k=1;$k<=3;$k++){
                    if(!is_null($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)})){
                        if($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} > 0){
                            $info[sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)] = $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }
        }


        return view(
            'user.reservations.confirm',
            compact(
                'plan',
                'companies',
                'info',
                'prices',
                'date',
                'weekday',
                'stock',
                'stock_price_types',
                'request',
                'prices_name',
                'prices_types'
            )
        );
    }

    // 詳細画面
    public function show($id)
    {
    }

    // 編集画面
    public function edit($id)
    {
        $reservations = Reservation::find($id);

        $categories = Genre::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByRaw('MIN(id)')
            ->get();
        $genres = Genre::all();
        $dt = new Carbon($reservations->fixed_datetime);
        // 料金区分２０以上対応
        $typeid=0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservations->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservations->Number_of_reservations);
            $count_old_member = 0;
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k), (array)$Number_of_reservations)){
                            $typeid = $i;
                            $prtype = $j;
                            $count_old_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }
        }
        $prices = Price::select()
                    ->where('plan_id' , $reservations->plan_id)
                    ->where('type' , $typeid)
                    ->get();
        $priceName = PriceType::select()
                    ->where('number' , $typeid)
                    ->first();

        $plan = Plan::select()
                ->where('id', $reservations->plan_id)
                ->with('activities')
                ->with('prices')
                ->first();
        if($plan->boarding != null){
            $pieces = explode(',',$plan->boarding);
        }
        else{
            $pieces = null;
        }
        if($plan->drop != null){
            $drops = explode(',',$plan->drop);
        }
        else{
            $drops = null;
        }


        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];
        // 祝日判定
        $holidays = Yasumi::create('Japan', $dt->format('Y'));
        foreach ($holidays->getHolidayDates() as $holiday) {
            if ($holiday == $dt->format('Y-m-d')) {
                $weekday = 'holiday';
            }
        }
        //クレジット決済キャンセル用
        $Credit_Cancels = CreditCancel::where('reservation_id', $id)->latest()->get();
        $Sum_refund_amount = CreditCancel::where(['reservation_id' => $id, 'cancel_status' => 'OK'])->sum('refund_amount');
        return view(
            'client.reservations.edit',
            compact('reservations','pieces','drops' ,'categories', 'genres', 'weekday','prices','priceName', 'Number_of_reservations', 'Credit_Cancels', 'Sum_refund_amount', 'typeid')
        );
    }

    // 決済メール送信のみ
    public function sendPaymentMail($id)
    {
        ini_set('memory_limit', '256M');
        $reservation = Reservation::find($id);

        $SendMailDate = new Carbon();
        $reservation->send_mail_at = $SendMailDate;
        $reservation->save();

        $prices = Price::select()
        ->where('plan_id' , $reservation->plan_id)
        ->where('type' , $reservation->price_type)
        ->get();
        $priceName = PriceType::select()
                    ->where('number' , $reservation->price_type)
                    ->first();
        // 料金区分２０以上対応
        $typeid=0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            $count_old_member = 0;
            $typeid = 0;
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                            $typeid = $i;
                            $prtype = $j;
                            $count_old_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }
        }
        $prices = Price::select()
                    ->where('plan_id' , $reservation->plan_id)
                    ->where('type' , $typeid)
                    ->get();
        $priceName = PriceType::select()
                        ->where('number' , $typeid)
                        ->first();
        //$reservation->save();
        // 合計金額セット
        $amount = 0;
        $dt = new Carbon($reservation->fixed_datetime);
        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];
        // 祝日判定
        $holidays = Yasumi::create('Japan', $dt->format('Y'));
        foreach ($holidays->getHolidayDates() as $holiday) {
            if ($holiday == $dt->format('Y-m-d')) {
                $weekday = 'holiday';
            }
        }
        // 料金区分２０以上対応
        // 合計金額リセット
        $amount = 0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            foreach ($prices as $price) {
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                                $amount += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} * $price->{sprintf('%s_%d', $byDay[$j],$k)};
                            }
                        }
                    }
                }
            }
            if(array_key_exists('custom_flg', $Number_of_reservations)){
                if($Number_of_reservations->custom_flg == 1){
                    $amount = 0;
                    for($j=1;$j<=20;$j++){
                        $amount += $Number_of_reservations->typec_price->{$j} * $Number_of_reservations->typec_number->{$j};
                    }
                }
            }
        }
        // 決済用URLメール通知
        // パラメーターを暗号化
        $param_card = [
            'payment_method' => 0,
            'reservation_id' => $reservation->id,
        ];
        $param_cvs = [
            'payment_method' => 1,
            'reservation_id' => $reservation->id,
        ];
        $bank = Bankaccount::find($reservation->plan->company->id);
        $payment_limit = Helpers::calcPaymentDeadline($SendMailDate, $reservation->plan->payment_plus_day, $reservation->plan->payment_final_deadline);

        // 決済方法ごとにメールテンプレートを分岐
        switch ($reservation->payment_method) {
            case PaymentMethodConstants::SPOT:
                Mail::send(
                    ['text' => 'user.reservations.spotemail'],
                    [
                        'number' => $reservation->order_id,
                        'plan' => $reservation->plan->name,
                        'date' => date(
                            'Y年m月d日',
                            strtotime($reservation->fixed_datetime)
                        ),
                        'activity' => $reservation->activity_date,
                        'name_last' => $reservation->user->name_last,
                        'kana_last' => $reservation->user->kana_last,
                        'name_first' => $reservation->user->name_first,
                        'kana_first' => $reservation->user->kana_first,
                        'postalcode' => $reservation->user->postalcode,
                        'prefecture' => $reservation->user->prefecture,
                        'address' => $reservation->user->address,
                        'birth_year' => $reservation->user->birth_year,
                        'birth_month' => $reservation->user->birth_month,
                        'birth_day' => $reservation->user->birth_day,
                        'email' => $reservation->user->email,
                        'tel' => $reservation->user->tel,
                        'tel2' => $reservation->user->tel2,
                        'reservation' => $reservation,
                        'amount' => $amount,
                        'weekday' => $weekday,
                        'bank' => $bank,
                        'payment' => '現地払い',
                        'prices' => $prices,
                        'priceName' => $priceName,
                        'type_id' => $typeid,
                        'payment_limit' => $payment_limit,
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->bcc([config('mail.custom.addresses.main')])
                                ->from(config('mail.custom.addresses.main'))
                                ->subject('【予約確定】長野電鉄株式会社');
                        }
                    }
                );
                break;
            case PaymentMethodConstants::PREPAY:
                Mail::send(
                    ['text' => 'user.reservations.prepayemail'],
                    [
                        'number' => $reservation->order_id,
                        'plan' => $reservation->plan->name,
                        'date' => date(
                            'Y年m月d日',
                            strtotime($reservation->fixed_datetime)
                        ),
                        'activity' => $reservation->activity_date,
                        'name_last' => $reservation->user->name_last,
                        'kana_last' => $reservation->user->kana_last,
                        'name_first' => $reservation->user->name_first,
                        'kana_first' => $reservation->user->kana_first,
                        'postalcode' => $reservation->user->postalcode,
                        'prefecture' => $reservation->user->prefecture,
                        'address' => $reservation->user->address,
                        'email' => $reservation->user->email,
                        'tel' => $reservation->user->tel,
                        'tel2' => $reservation->user->tel2,
                        'birth_year' => $reservation->user->birth_year,
                        'birth_month' => $reservation->user->birth_month,
                        'birth_day' => $reservation->user->birth_day,
                        'reservation' => $reservation,
                        'amount' => $amount,
                        'weekday' => $weekday,
                        'bank' => $bank,
                        'payment' => '銀行振込',
                        'prices' => $prices,
                        'priceName' => $priceName,
                        'type_id' => $typeid,
                        'payment_limit' => $payment_limit,
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->bcc([config('mail.custom.addresses.main')])
                                ->from(config('mail.custom.addresses.main'))
                                ->subject('【予約確定】長野電鉄株式会社');
                        }
                    }
                );
                break;
            case PaymentMethodConstants::CVS:
                Mail::send(
                    ['text' => 'user.reservations.cvsemail'],
                    [
                        'url_cvs' => secure_url('pay?prm='.encrypt($param_cvs)),
                        'number' => $reservation->order_id,
                        'plan' => $reservation->plan->name,
                        'date' => date(
                            'Y年m月d日',
                            strtotime($reservation->fixed_datetime)
                        ),
                        'activity' => $reservation->activity_date,
                        'name_last' => $reservation->user->name_last,
                        'name_first' => $reservation->user->name_first,
                        'email' => $reservation->user->email,
                        'tel' => $reservation->user->tel,
                        'tel2' => $reservation->user->tel2,
                        'reservation' => $reservation,
                        'amount' => $amount,
                        'weekday' => $weekday,
                        'prices' => $prices,
                        'priceName' => $priceName,
                        'type_id' => $typeid,
                        'payment_limit' => $payment_limit,
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->bcc([config('mail.custom.addresses.main')])
                                ->from(config('mail.custom.addresses.main'))
                                ->subject('【入金依頼】長野電鉄株式会社');
                        }
                    }
                );
                break;
            case PaymentMethodConstants::CARD:
                Mail::send(
                    ['text' => 'user.reservations.cardemail'],
                    [
                        'url_card' => secure_url('pay?prm='.encrypt($param_card)),
                        'number' => $reservation->order_id,
                        'plan' => $reservation->plan->name,
                        'date' => date(
                            'Y年m月d日',
                            strtotime($reservation->fixed_datetime)
                        ),
                        'activity' => $reservation->activity_date,
                        'name_last' => $reservation->user->name_last,
                        'name_first' => $reservation->user->name_first,
                        'email' => $reservation->user->email,
                        'tel' => $reservation->user->tel,
                        'tel2' => $reservation->user->tel2,
                        'reservation' => $reservation,
                        'amount' => $amount,
                        'weekday' => $weekday,
                        'prices' => $prices,
                        'priceName' => $priceName,
                        'type_id' => $typeid,
                        'payment_limit' => $payment_limit,
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->bcc([config('mail.custom.addresses.main')])
                                ->from(config('mail.custom.addresses.main'))
                                ->subject('【決済依頼】長野電鉄株式会社');
                        }
                    }
                );
                break;
            default:
                throw new LogicException();
        }
        return redirect()
            ->back()
            ->with('message', '送信が完了しました');
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        ini_set('memory_limit', '256M');
        $rules = [
            'status' => ['required', 'string', 'max:7'],
            'type0_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type1_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type2_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type3_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type4_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type5_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type6_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type7_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type8_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type9_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type10_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type11_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type12_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type13_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type14_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type15_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type16_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type17_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type18_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type19_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'type20_number' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'memo' => ['nullable', 'string', 'max:200'],
        ];
        $this->validate($request, $rules);
        for ($i = 0; $i < 6; $i++) {
            if (!$request->{'type' . $i . '_number'}) {
                $request->{'type' . $i . '_number'} = 0;
            }
        }
        $reservation = Reservation::find($id);
        // ステータス：キャンセルからステータスを変更しようとした場合、実態の整合性が取れなくなる恐れがあるため、エラーとする
        if ($reservation->status == 'キャンセル' && $reservation->status != $request->status){
            throw ValidationException::withMessages(['status_error' => 'キャンセル済の為、他ステータスに変更できません。']);
        }
        $old_ccrec = CreditCancel::where('reservation_id', $id)->latest()->first();
        if (!is_null($old_ccrec)){
            if ($old_ccrec->cancel_status != 'OK'){
                if ($request->{'credit_cancel_price'} == null || !is_numeric($request->{'credit_cancel_price'}) || strlen($request->{'credit_cancel_price'}) > 8 || $request->{'credit_cancel_price'} < 0) {
                    throw ValidationException::withMessages(['no_price_error' => '返金額を正しく入力してください（正の整数値、最大8桁まで）']);
                }
            }
        }else{
            if ($request->{'credit_cancel_price'} == null || !is_numeric($request->{'credit_cancel_price'}) || strlen($request->{'credit_cancel_price'}) > 8 || $request->{'credit_cancel_price'} < 0) {
                throw ValidationException::withMessages(['no_price_error' => '返金額を正しく入力してください（正の整数値、最大8桁まで）']);
            }
        }
        // 人数変更＝価格変更があった場合
        $count_old_member = 0;
        // 料金区分２０以上対応
        $typeid=0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            $count_old_member = 0;
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),(array)$Number_of_reservations)){
                            $typeid = $i;
                            $count_old_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }



            // if(array_key_exists('custom_flg', (array)$Number_of_reservations)){
            //     if($Number_of_reservations->custom_flg == 1){
            //         for($j=1;$j<=20;$j++){
            //             $count_old_member += $Number_of_reservations->typec_number->{$j};
            //         }
            //     }
            // }
        }
        $custom_flg = false;
        if(array_key_exists('custom_flg', (array)$Number_of_reservations)){
            if($Number_of_reservations->custom_flg == 1){
                $custom_flg = true;
            }
        }
        $count_requested_member = 0;
        if($custom_flg){
            for($i=1;$i<=20;$i++){
                if(!is_null($request->{sprintf('typec_%d_number', $i)})){
                    if($request->{sprintf('typec_%d_number', $i)} > 0){
                        $count_requested_member += $request->{sprintf('typec_%d_number', $i)};
                    }
                }
            }
        }else{
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(!is_null($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)})){
                            if($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} > 0){
                                $count_requested_member += $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                            }
                        }
                    }
                }
            }
        }
        $reservation->status = $request->status;
        $reservation->payment_method = $request->payment_method;

        $reservation->add_name_first    = $request->add_name_first;
        $reservation->add_name_last     = $request->add_name_last;
        $reservation->add_kana_first    = $request->add_kana_first;
        $reservation->add_kana_last     = $request->add_kana_last;
        $reservation->add_age           = $request->add_age;
        $reservation->add_birth         = $request->add_birth;
        $reservation->add_postalcode    = $request->add_postalcode;
        $reservation->add_prefecture    = $request->add_prefecture;
        $reservation->add_address       = $request->add_address;
        $reservation->add_telephone     = $request->add_telephone;
        $reservation->add_building      = $request->add_building;

        $reservation->add_boarding      = $request->add_boarding;
        $reservation->add_drop          = $request->add_drop;


        $reservation->companion_name_first  = json_encode($request->companion_name_first);
        $reservation->companion_name_last   = json_encode($request->companion_name_last);
        $reservation->companion_kana_first  = json_encode($request->companion_kana_first);
        $reservation->companion_kana_last   = json_encode($request->companion_kana_last);
        $reservation->companion_age         = json_encode($request->companion_age);
        $reservation->companion_gender      = json_encode($request->companion_gender);
        $reservation->companion_postalCode  = json_encode($request->companion_postalCode);
        $reservation->companion_prefecture  = json_encode($request->companion_prefecture);
        $reservation->companion_address     = json_encode($request->companion_address);
        $reservation->companion_extended    = json_encode($request->companion_extended);
        $reservation->companion_birth       = json_encode($request->companion_birth);
        $reservation->companion_boarding    = json_encode($request->companion_boarding);
        $reservation->companion_drop        = json_encode($request->companion_drop);


        $reservation->type0_number = $request->type0_number;
        $reservation->type1_number = $request->type1_number;
        $reservation->type2_number = $request->type2_number;
        $reservation->type3_number = $request->type3_number;
        $reservation->type4_number = $request->type4_number;
        $reservation->type5_number = $request->type5_number;
        $reservation->type6_number = $request->type6_number;
        $reservation->type7_number = $request->type7_number;
        $reservation->type8_number = $request->type8_number;
        $reservation->type9_number = $request->type9_number;
        $reservation->type10_number = $request->type10_number;
        $reservation->type11_number = $request->type11_number;
        $reservation->type12_number = $request->type12_number;
        $reservation->type13_number = $request->type13_number;
        $reservation->type14_number = $request->type14_number;
        $reservation->type15_number = $request->type15_number;
        $reservation->type16_number = $request->type16_number;
        $reservation->type17_number = $request->type17_number;
        $reservation->type18_number = $request->type18_number;
        $reservation->type19_number = $request->type19_number;
        $reservation->type20_number = $request->type20_number;
        $reservation->memo = $request->memo;
        $reservation->memo2 = $request->memo2;
        if ($count_old_member != $count_requested_member) {
            // 合計金額セット
            $amount = 0;
            $dt = new Carbon($request->date);
            $week_map = [
                0 => 'sunday',
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
            ];
            $day_of_week = $dt->dayOfWeek;
            $weekday = $week_map[$day_of_week];
            // 祝日判定
            $holidays = Yasumi::create('Japan', $dt->format('Y'));
            foreach ($holidays->getHolidayDates() as $holiday) {
                if ($holiday == $dt->format('Y-m-d')) {
                    $weekday = 'holiday';
                }
            }
            // 料金区分２０以上対応
            // 合計金額リセット
            $amount = 0;
            foreach ($reservation->plan->prices as $price) {
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(!is_null($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)})){
                                if($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} > 0 && $price->type == $i){
                                    $amount += $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} * $price->{sprintf('%s_%d', $byDay[$j],$k)};
                                }
                            }
                        }
                    }
                }
            }
            // 料金区分２０以上対応
            $Number_of_reservations = [];
            $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(!is_null($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)})){
                            if($request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} > 0){
                                $Number_of_reservations[sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)] = $request->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                            }
                        }
                    }
                }
            }
            if($custom_flg){
                $Number_of_reservations = [];
                $Number_of_reservations['custom_flg'] = 1;
                // 合計金額リセット
                $amount = 0;
                for($k=1;$k<=20;$k++){
                    $Number_of_reservations['price_name'][$k] = $request->{'price_name'.$k};
                    $Number_of_reservations['typec_price'][$k] = $request->{'typec_'.$k.'_price'};
                    $Number_of_reservations['typec_number'][$k] = $request->{'typec_'.$k.'_number'};
                    $amount += $request->{'typec_'.$k.'_price'} * $request->{'typec_'.$k.'_number'};
                }
            }
            $reservation->Number_of_reservations = json_encode($Number_of_reservations);
            if ($reservation->payment_method == PaymentMethodConstants::CARD && $reservation->status == '予約確定') {
                $mstatus = $this->cardReAuthorize(
                    $reservation,
                    $request,
                    $amount,
                    $reservation->jpo1,
                    $reservation->jpo2,
                    $count_old_member,
                    $count_requested_member
                );
                Log::info('change price result are: ' . $mstatus);
            }
        }
        if(isset($request->custom_flg)){
            if($request->custom_flg == 1){
                if(is_array($Number_of_reservations)){
                    $Number_of_reservations['custom_flg'] = 1;
                }else{
                    $Number_of_reservations = (array)$Number_of_reservations;
                    $Number_of_reservations['custom_flg'] = 1;
                }
            }
        }
        if($custom_flg){
            $Number_of_reservations = [];
            $Number_of_reservations['custom_flg'] = 1;
            for($k=1;$k<=20;$k++){
                $Number_of_reservations['price_name'][$k] = $request->{'price_name'.$k};
                $Number_of_reservations['typec_price'][$k] = $request->{'typec_'.$k.'_price'};
                $Number_of_reservations['typec_number'][$k] = $request->{'typec_'.$k.'_number'};
            }
        }
        $reservation->Number_of_reservations = json_encode($Number_of_reservations);
        $reservation->save();
        // コンビニ決済キャンセルの場合
        if (
            $request->status == 'キャンセル' &&
            $reservation->payment_method == PaymentMethodConstants::CVS &&
            $reservation->order_id
        ) {
            $mstatus = $this->cvsCancel($reservation->order_id);
            if ($mstatus != 'success') {
                return redirect()
                    ->back()
                    ->withErrors([
                        'DGFT側のコンビニ決済がキャンセルできませんでした。詳細はMAP管理画面で該当の予約番号をご確認ください',
                    ]);
            }
        }
        // クレジットカード決済キャンセルの場合
        if (
            $request->status == 'キャンセル' &&
            $reservation->payment_method == PaymentMethodConstants::CARD &&
            $reservation->order_id
        ) {
            if ($request->credit_cancel_flg != 1){
                return redirect('/client/reservations/edit/' . $id)->with('message', '変更が完了しました');
            }
            $Sum_refund_amount = CreditCancel::where(['reservation_id' => $id, 'cancel_status' => 'OK'])->sum('refund_amount');
            $Sum_refund_amount += $request->credit_cancel_price;
            $reservation->status = '一部返金';
            $reservation->save();
            if(!is_null($reservation->Number_of_reservations)){
                $Number_of_reservations = json_decode($reservation->Number_of_reservations);
                $count_member = 0;
                // 合計金額リセット
                $amount = 0;
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                                $typeid = $i;
                                $count_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                                foreach ($reservation->plan->prices as $price) {
                                    if($price->type == $typeid){
                                        $amount += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} * $price->{sprintf('%s_%d', $byDay[$j],$k)};
                                    }
                                }
                            }
                        }
                    }
                }
                if(array_key_exists('custom_flg', $Number_of_reservations)){
                    if($Number_of_reservations->custom_flg == 1){
                        $amount = 0;
                        for($j=1;$j<=20;$j++){
                            $amount += $Number_of_reservations->typec_price->{$j} * $Number_of_reservations->typec_number->{$j};
                        }
                    }
                }
            }
            $sum_amount = $amount;
            if ($request->{'credit_cancel_price'} > 1 && $Sum_refund_amount <= $sum_amount){
                $mstatus = $this->cardCancel($reservation->order_id, $reservation->id, $request->{'credit_cancel_price'});
            } elseif ($Sum_refund_amount > $sum_amount ){
                return redirect()->back()->withErrors(['クレジットカード決済がキャンセルできません。返金額はクレジットカード決済金額以下でご入力ください。']);
            } elseif ($request->{'credit_cancel_price'} == 0){
                $mstatus = 'success';
            }
            if ($mstatus != 'success') {
                //クレジット決済キャンセル用
                if(CreditCancel::where('reservation_id', $id)->exists()){
                    $CreditCancel = CreditCancel::where('reservation_id', $id)->latest()->first();
                    if($CreditCancel->cancel_status != 'OK'){
                        $CreditCancel->cancel_status = 'NG';
                        $CreditCancel->refund_amount = $request->credit_cancel_price;
                        $CreditCancel->save();
                    }else{
                        $CreditCancelNew = new CreditCancel;
                        $CreditCancelNew->reservation_id = $id;
                        $CreditCancelNew->refund_amount = $request->credit_cancel_price;
                        $CreditCancelNew->cancel_status = 'NG';
                        $CreditCancelNew->save();
                    }
                }else{
                    $CreditCancel = new CreditCancel;
                    $CreditCancel->reservation_id = $id;
                    $CreditCancel->refund_amount = $request->{'credit_cancel_price'};
                    $CreditCancel->cancel_status = 'NG';
                    $CreditCancel->save();
                }
                /*return redirect()
                    ->back()
                    ->withErrors([
                        'DGFT側のクレジットカード決済がキャンセルできませんでした。詳細はMAP管理画面で該当の予約番号をご確認ください',
                    ]);*/
                return redirect('/client/reservations/edit/' . $id .'#credit_cancel_price')->withErrors(['DGFT側のクレジットカード決済がキャンセルできませんでした。詳細はMAP管理画面で該当の予約番号をご確認ください']);
            } else {
                if($Sum_refund_amount == $sum_amount){
                    $reservation->status = 'キャンセル';
                    $reservation->save();
                }
                //クレジット決済キャンセル用
                if(CreditCancel::where('reservation_id', $id)->exists()){
                    $CreditCancel = CreditCancel::where('reservation_id', $id)->latest()->first();
                    if($CreditCancel->cancel_status != 'OK'){
                        $CreditCancel->cancel_status = 'OK';
                        $CreditCancel->refund_amount = $request->credit_cancel_price;
                        $CreditCancel->save();
                    }else{
                        $CreditCancelNew = new CreditCancel;
                        $CreditCancelNew->reservation_id = $id;
                        $CreditCancelNew->refund_amount = $request->credit_cancel_price;
                        $CreditCancelNew->cancel_status = 'OK';
                        $CreditCancelNew->save();
                    }
                }else{
                    $CreditCancel = new CreditCancel;
                    $CreditCancel->reservation_id = $id;
                    $CreditCancel->refund_amount = $request->{'credit_cancel_price'};
                    $CreditCancel->cancel_status = 'OK';
                    $CreditCancel->save();
                }
            }
        }
        if(isset($request->custom_flg)){
            if($request->custom_flg == 1){
                return redirect('/client/reservations/edit/' . $id . '#price_name1')->with('message', '価格表がカスタマイズ可能になりました。');
            }
        }
        return redirect()
            ->back()
            ->with('message', '変更が完了しました');
    }

    // 複製処理
    public function replicate($id)
    {
    }

    // 削除処理
    public function destroy($id)
    {
        // TODO: NAGADEN-13
        throw new RuntimeException();

//        $reservations = Reservation::destroy($id);
//        // リレーションレコード削除1
//        Activity::where('plan_id', $id)->delete();
//        // リレーションレコード削除2
//        Price::where('plan_id', $id)->delete();
//        return redirect()->back();
    }

    // 選択削除処理
    public function destroySelected(Request $request)
    {
        // TODO: NAGADEN-13
        throw new RuntimeException();

//        $ids = explode(',', $request->ids);
//        $reservations = Reservation::destroy($ids);
//        // リレーションレコード削除
//        foreach ($ids as $id) {
//            Activity::where('plan_id', $id)->delete();
//            Price::where('plan_id', $id)->delete();
//        }
//        return redirect()->back();
    }

    public function csvSelected(Request $request)
    {

        $ids = explode(',', $request->ids);
        $reservations = Reservation::whereIn('id', $ids)->get();

        //        // コールバック関数に１行ずつ書き込んでいく処理を記述
        //        $callback = function () use ($reservations) {
        //            // 出力バッファをopen
        //            $stream = fopen('php://output', 'w');
        //            // 文字コードをShift-JISに変換
        //            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
        //            // ヘッダー行
        //            fputcsv($stream, [
        //            'ID','プラン名','予約番号','受付タイプ','予約ステータス','体験日時(1)','予約受付日時','予約確定日時','予約者','予約者への質問','予約者からの回答','支払方法','その他 備考・特記事項',
        //            ]);
        //            // データ
        //            $companies = Company::orderBy('id', 'desc');
        //            // ２行目以降の出力
        //        // cursor()メソッドで１レコードずつストリームに流す処理を実現できる。
        //            foreach ($reservations->cursor() as $reservation) {
        //                fputcsv($stream, [
        //                    $reservation->id,
        //                ]);
        //            }
        //            fclose($stream);
        //        };

        //        // 保存するファイル名




        // UTF-8からSJIS-winへ変換するフィルター
        //stream_filter_append($fp, 'convert.iconv.UTF-8/CP932//TRANSLIT', STREAM_FILTER_WRITE);

		header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=reservation.csv');
        header('Content-Transfer-Encoding: binary');
		$header_args = array(
			'プランID',
			'プラン名',
			'予約番号',
			'受付タイプ',
			'予約ステータス',
			'予約者への質問',
			'予約者からの回答',
			'支払方法',
			'その他 備考・特記事項',);


		foreach($header_args as $key => $header){
			$header_args[$key] = mb_convert_encoding($header , 'SJIS-win', 'UTF-8');
		}

		$fp = fopen('php://output', 'w');
		ob_end_clean();
		fputcsv($fp,$header_args,',','"');
        foreach ($reservations as $reservation) {
            $row = [];
            $row[] = $reservation->id;
            $row[] = mb_convert_encoding($reservation->plan->name, 'SJIS-win', 'UTF-8');
            $row[] = $reservation->order_id;
            if ($reservation->plan->res_type == '0') {
                $row[] = mb_convert_encoding('即時', 'SJIS-win', 'UTF-8');
            } elseif ($reservation->plan->res_type == '1') {
                $row[] = mb_convert_encoding('即時・リクエスト併用', 'SJIS-win', 'UTF-8');
            } else {
                $row[] = mb_convert_encoding('リクエスト予約', 'SJIS-win', 'UTF-8');
            }
            $row[] = mb_convert_encoding($reservation->status ,'SJIS-win', 'UTF-8');
			if(json_decode($reservation->plan->question_content , true) == null){
				$row[] = '';
			}
			else{
				$row[] = mb_convert_encoding(json_decode($reservation->plan->question_content , true)[0], 'SJIS-win', 'UTF-8');
			}

            if(json_decode($reservation->answer , true) == null){
                $row[] = '';
            }
            else{
                $row[] =  mb_convert_encoding(json_decode($reservation->answer , true)[0], 'SJIS-win', 'UTF-8');
            }
            if ($reservation->payment_method == PaymentMethodConstants::SPOT) {
                $row[] = mb_convert_encoding('現地払い', 'SJIS-win', 'UTF-8');
            } elseif ($reservation->payment_method == PaymentMethodConstants::PREPAY) {
                $row[] = mb_convert_encoding('銀行振込', 'SJIS-win', 'UTF-8');
            } elseif ($reservation->payment_method == PaymentMethodConstants::CVS) {
                $row[] = mb_convert_encoding('事前コンビニ決済', 'SJIS-win', 'UTF-8');
            } elseif ($reservation->payment_method == PaymentMethodConstants::CARD) {
                $row[] = mb_convert_encoding('事前クレジットカード決済', 'SJIS-win', 'UTF-8');
            } else {
                $row[] = '';
            }
            $row[] = mb_convert_encoding($reservation->memo,'SJIS-win', 'UTF-8');
            fputcsv($fp, $row ,',','"');
        }

        fclose($fp);




        exit();
    }

    // JSON返却
    public function json($user_id = null)
    {
        try {
            $query = Reservation::with([
                'user' => function ($q) {
                    $q->select(['id', 'name_last', 'name_first']);
                },
                'plan' => function ($q) {
                    $q->select(['id', 'name']);
                }
            ]);
            $query = $query->whereNotNull('number');
            if ($_GET['status'] ?? null) {
                $query = $query->where('status', $_GET['status']);
            }
            if ($_GET['date'] ?? null) {
                $query = $query->whereDate('created_at', $_GET['date']);
            }
            if ($_GET['startDay'] ?? null) {
                $query = $query->whereDate('fixed_datetime', $_GET['startDay']);
            }
            if (!$user_id) {
                $query = $query->where('status', '<>', '決済処理中');
            } else {
                $query = $query->where('user_id', $user_id);
            }
            $query = $query->orderBy('id', 'desc');
            $query = $query->select(['plan_id', 'user_id', 'order_id', 'id', 'status', 'fixed_datetime', 'payment_method', 'created_at']);
            $result = $query->get()->each(function (Reservation $reservation) {
                $reservation->user->setAppends([]);
            });
            return $this->resConversionJson($result);
        } catch (Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
    }

    // 上記メソッド内ファンクション
    private function resConversionJson($result, $statusCode = 200)
    {
        if (empty($statusCode) || $statusCode < 100 || $statusCode >= 600) {
            $statusCode = 500;
        }
        return response()->json(
            $result,
            $statusCode,
            ['Content-Type' => 'application/json'],
            JSON_UNESCAPED_SLASHES
        );
    }

    // 決済メール用処理
    public function emailToPay(Request $request)
    {
        $parameters = decrypt(request('prm'));
        $reservation_id = $parameters['reservation_id'];
        $payment_method_prm = $parameters['payment_method'];
        $reservation = Reservation::find($reservation_id);
        $reservation->save();
        $tokenApiKey = config('setting.mdk.token_api_key');
        $user = $reservation->user;
        $orderId = $reservation->number;
        // 合計金額セット
        $amount = 0;
        foreach ($reservation->plan->prices as $price) {
            if ($reservation->type0_number && $price->type == 0) {
                $amount += $reservation->type0_number * $price->price;
            } elseif ($reservation->type1_number && $price->type == 1) {
                $amount += $reservation->type1_number * $price->price;
            } elseif ($reservation->type2_number && $price->type == 2) {
                $amount += $reservation->type2_number * $price->price;
            } elseif ($reservation->type3_number && $price->type == 3) {
                $amount += $reservation->type3_number * $price->price;
            } elseif ($reservation->type4_number && $price->type == 4) {
                $amount += $reservation->type4_number * $price->price;
            } elseif ($reservation->type5_number && $price->type == 5) {
                $amount += $reservation->type5_number * $price->price;
            } elseif ($reservation->type6_number && $price->type == 6) {
                $amount += $reservation->type6_number * $price->price;
            } elseif ($reservation->type7_number && $price->type == 7) {
                $amount += $reservation->type7_number * $price->price;
            } elseif ($reservation->type8_number && $price->type == 8) {
                $amount += $reservation->type8_number * $price->price;
            } elseif ($reservation->type9_number && $price->type == 9) {
                $amount += $reservation->type9_number * $price->price;
            } elseif ($reservation->type10_number && $price->type == 10) {
                $amount += $reservation->type10_number * $price->price;
            } elseif ($reservation->type11_number && $price->type == 11) {
                $amount += $reservation->type11_number * $price->price;
            } elseif ($reservation->type12_number && $price->type == 12) {
                $amount += $reservation->type12_number * $price->price;
            } elseif ($reservation->type13_number && $price->type == 13) {
                $amount += $reservation->type13_number * $price->price;
            } elseif ($reservation->type14_number && $price->type == 14) {
                $amount += $reservation->type14_number * $price->price;
            } elseif ($reservation->type15_number && $price->type == 15) {
                $amount += $reservation->type15_number * $price->price;
            } elseif ($reservation->type16_number && $price->type == 16) {
                $amount += $reservation->type16_number * $price->price;
            } elseif ($reservation->type17_number && $price->type == 17) {
                $amount += $reservation->type17_number * $price->price;
            } elseif ($reservation->type18_number && $price->type == 18) {
                $amount += $reservation->type18_number * $price->price;
            } elseif ($reservation->type19_number && $price->type == 19) {
                $amount += $reservation->type19_number * $price->price;
            } elseif ($reservation->type20_number && $price->type == 20) {
                $amount += $reservation->type20_number * $price->price;
            }
        }
        // 料金区分２０以上対応
        $typeid=0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            $count_old_member = 0;
            $type_id = 0;
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                            $type_id = $i;
                            $prtype = $j;
                            $count_old_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }
        }
        $prices = Price::select()
                    ->where('plan_id' , $reservation->plan_id)
                    ->where('type' , $type_id)
                    ->get();
        $priceName = PriceType::select()
                        ->where('number' , $type_id)
                        ->first();
        // 料金区分２０以上対応
        // 合計金額リセット
        $amount = 0;
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            foreach ($prices as $price) {
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                                $amount += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} * $price->{sprintf('%s_%d', $byDay[$j],$k)};
                            }
                        }
                    }
                }
            }
            if(array_key_exists('custom_flg', $Number_of_reservations)){
                if($Number_of_reservations->custom_flg == 1){
                    $amount = 0;
                    for($j=1;$j<=20;$j++){
                        $amount += $Number_of_reservations->typec_price->{$j} * $Number_of_reservations->typec_number->{$j};
                    }
                }
            }
        }

        $payment_method_str = null;
        switch ($payment_method_prm) {
            case 0:
                $payment_method_str = 'クレジットカード決済';
                break;
            case 1:
                $payment_method_str = 'コンビニ決済';
                break;
            default:
                # code...
                break;
        }

        $dt = new Carbon($reservation->fixed_datetime);
        // 期限チェック
        if(is_null($reservation->send_mail_at)){
            $SendMailDate = $reservation->created_at;
        }else{
            $SendMailDate = new Carbon($reservation->send_mail_at);
        }
        $deadline = Helpers::calcPaymentDeadline($SendMailDate, $reservation->plan->payment_plus_day, $reservation->plan->payment_final_deadline);
        if(Carbon::now() > $deadline){
            return view(
                'over.index',
                compact(
                    'reservation',
                    'amount',
                    'orderId',
                    'prices',
                    'priceName',
                    'type_id',
                    'payment_method_str'
                )
            );
        }
        $date = $reservation->fixed_datetime;
        $dt = new Carbon($date);
        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];
        // 祝日判定
        $holidays = Yasumi::create('Japan', $dt->format('Y'));
        foreach ($holidays->getHolidayDates() as $holiday) {
            if ($holiday == $dt->format('Y-m-d')) {
                $weekday = 'holiday';
            }
        }
        if ($payment_method_prm == 0) {
            return view(
                'card.index',
                compact(
                    'reservation',
                    'amount',
                    'user',
                    'tokenApiKey',
                    'orderId',
                    'weekday',
                    'prices',
                    'priceName',
                    'type_id'
                )
            );
        } elseif ($payment_method_prm == 1) {
            return view(
                'cvs.index',
                compact(
                    'reservation',
                    'amount',
                    'user',
                    'tokenApiKey',
                    'orderId',
                    'weekday',
                    'prices',
                    'priceName',
                    'type_id'
                )
            );
        } else {
            return redirect('list.php');
        }
    }

    // 表示順変更
    public function sortAjax(Request $request)
    {
        $id = $request->id;
        $sort = $request->sort;
        if ($sort > 0 && $sort < 1000) {
            $plan = Reservation::find($id);
            $plan->sort = $sort;
            $plan->save();
            return $request->sort;
        } else {
            return 0;
        }
    }

    // カード決済キャンセル
    public function cardCancel($order_id, $reservation_id, $refund_amount)
    {
        $logger = Log::channel('tgmdk')->getLogger();
        if ($logger instanceof LoggerInterface) {
            TGMDK_Logger::setLogger($logger);
        }
        $request_data = new CardCancelRequestDto();
        $request_data->setOrderId($order_id);
        if(!is_null($refund_amount)){
            $request_data->setAmount($refund_amount);
        }
        $old_ccrec = CreditCancel::where('reservation_id', $reservation_id)->latest()->first();
        if (!is_null($old_ccrec)){
            if ($old_ccrec->cancel_status == 'NG'){
                $request_data->setWithDirect('true');
            }
        }

        //$request_data->setJpo(
        //    Helpers::generateJpo($request->request->get("jpo1"), $request->request->get("jpo2"))
        //);

        /*
         * 設定ファイルのパスを手動で指定する場合は以下のようにパスを指定してTGMDK_Configクラスのインスタンス生成をしておく
         * TGMDK_Config::getInstance("/home/test/laravel_sample/config/3GPSMDK.properties");
         */
        TGMDK_Config::getInstance();
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        /*
         * マーチャントIDとマーチャント認証鍵を動的に設定する場合はexecuteメソッドの第2引数に以下のようにセットする
         */
        /*$props['merchant_ccid'] = '';
        $props['merchant_secret_key'] =
            '';*/
        $response_data = $transaction->execute($request_data);
        if ($response_data instanceof CardCancelResponseDto) {
            $mstatus = $response_data->getMstatus();
            // 予約者へメール通知
            /*
            $reservation = Reservation::find($request->reservation_id);
            $dt = new Carbon($reservation->fixed_datetime);
            $week_map = [
                0 => 'sunday',
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
            ];
            $day_of_week = $dt->dayOfWeek;
            $weekday = $week_map[$day_of_week];
            // 祝日判定
            $holidays = Yasumi::create('Japan', $dt->format('Y'));
            foreach ($holidays->getHolidayDates() as $holiday) {
                if ($holiday == $dt->format('Y-m-d')) {
	    	$weekday = 'holiday';
                }
            }
            Mail::send(['text' => 'user.reservations.email'], [
                "number" => $reservation->order_id,
                "plan" => $reservation->plan->name,
                "date" => date('Y年m月d日', strtotime($reservation->fixed_datetime)),
                "activity" => $reservation->activity_date,
                "name_last" => $reservation->user->name_last,
                "name_first" => $reservation->user->name_first,
                "email" => $reservation->user->email,
                "tel" => $reservation->user->tel,
                "tel2" => $reservation->user->tel2,
                "reservation" => $reservation,
                "payment" => 'クレジットカード決済',
                "haraikomiUrl" => null,
                "weekday" => $weekday,
                "amount" => $request->amount
            ], function($message) use($reservation) {
                if ($reservation->user->email) {
                    $message
                    ->to($reservation->user->email)
                    ->from(config('mail.custom.addresses.main'))
                    ->subject("【長野電鉄株式会社】予約確定メール");
	        }
            });
            // ベリトランスオーダーIDをDBへ格納
            $reservation->order_id = $request->request->get("orderId");
            // 決済ステータスをDBへ格納
            $reservation->status = '予約確定';
            $reservation->save();
            // 在庫を減数
            $stock = Stock::select()
            ->where('plan_id', $reservation->plan->id)
            ->where('res_date', date('Y-m-d', strtotime($reservation->fixed_datetime)))
            ->first();
            if ($stock) {
                if ($reservation->plan->res_limit_flag == 0) {
                    // 予約人数をカウント
                    $count_member = 0;
                    for ($i = 0; $i <= 20 ; $i++) {
                        $count = $reservation->{'type'. $i . '_number'};
                        if ($count > 0) {
                            $count_member += $count;
                        }
                    }
                    $stock->limit_number = $stock->limit_number - $count_member;
                    $stock->save();
                } else {
                    $stock->limit_number = $stock->limit_number - 1;
                    $stock->save();
                }
            }
            //return view('user.reservations.result' , compact('req'));
            return redirect('/card/result/' . $request->request->get("orderId"));
        }
        return view('card/index')->with(
            [
                'tokenApiKey' => Config::get('sample_setting.token.token_api_key'),
                'amount' => $request->request->get("amount")
            ]);
*/
        }
        Log::info('card cancel status is : ' . $mstatus);
        return $mstatus;
    }

    // コンビニ決済キャンセル
    public function cvsCancel($order_id)
    {
        $logger = Log::channel('tgmdk')->getLogger();
        if ($logger instanceof LoggerInterface) {
            TGMDK_Logger::setLogger($logger);
        }
        $request_data = new CvsCancelRequestDto();
        $request_data->setOrderId($order_id);

        //$request_data->setJpo(
        //    Helpers::generateJpo($request->request->get("jpo1"), $request->request->get("jpo2"))
        //);

        /*
         * 設定ファイルのパスを手動で指定する場合は以下のようにパスを指定してTGMDK_Configクラスのインスタンス生成をしておく
         * TGMDK_Config::getInstance("/home/test/laravel_sample/config/3GPSMDK.properties");
         */
        TGMDK_Config::getInstance();
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        /*
         * マーチャントIDとマーチャント認証鍵を動的に設定する場合はexecuteメソッドの第2引数に以下のようにセットする
         */
        /*$props['merchant_ccid'] = '';
        $props['merchant_secret_key'] =
            '';*/
        $response_data = $transaction->execute($request_data);
        if ($response_data instanceof CvsCancelResponseDto) {
            $mstatus = $response_data->getMstatus();
            // 予約者へメール通知
            /*
            $reservation = Reservation::find($request->reservation_id);
            $dt = new Carbon($reservation->fixed_datetime);
            $week_map = [
                0 => 'sunday',
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
            ];
            $day_of_week = $dt->dayOfWeek;
            $weekday = $week_map[$day_of_week];
            // 祝日判定
            $holidays = Yasumi::create('Japan', $dt->format('Y'));
            foreach ($holidays->getHolidayDates() as $holiday) {
                if ($holiday == $dt->format('Y-m-d')) {
	    	$weekday = 'holiday';
                }
            }
            Mail::send(['text' => 'user.reservations.email'], [
                "number" => $reservation->order_id,
                "plan" => $reservation->plan->name,
                "date" => date('Y年m月d日', strtotime($reservation->fixed_datetime)),
                "activity" => $reservation->activity_date,
                "name_last" => $reservation->user->name_last,
                "name_first" => $reservation->user->name_first,
                "email" => $reservation->user->email,
                "tel" => $reservation->user->tel,
                "tel2" => $reservation->user->tel2,
                "reservation" => $reservation,
                "payment" => 'クレジットカード決済',
                "haraikomiUrl" => null,
                "weekday" => $weekday,
                "amount" => $request->amount
            ], function($message) use($reservation) {
                if ($reservation->user->email) {
                    $message
                    ->to($reservation->user->email)
                    ->from(config('mail.custom.addresses.main'))
                    ->subject("【長野電鉄株式会社】予約確定メール");
	        }
            });
            // ベリトランスオーダーIDをDBへ格納
            $reservation->order_id = $request->request->get("orderId");
            // 決済ステータスをDBへ格納
            $reservation->status = '予約確定';
            $reservation->save();
            // 在庫を減数
            $stock = Stock::select()
            ->where('plan_id', $reservation->plan->id)
            ->where('res_date', date('Y-m-d', strtotime($reservation->fixed_datetime)))
            ->first();
            if ($stock) {
                if ($reservation->plan->res_limit_flag == 0) {
                    // 予約人数をカウント
                    $count_member = 0;
                    for ($i = 0; $i <= 20 ; $i++) {
                        $count = $reservation->{'type'. $i . '_number'};
                        if ($count > 0) {
                            $count_member += $count;
                        }
                    }
                    $stock->limit_number = $stock->limit_number - $count_member;
                    $stock->save();
                } else {
                    $stock->limit_number = $stock->limit_number - 1;
                    $stock->save();
                }
            }
            //return view('user.reservations.result' , compact('req'));
            return redirect('/card/result/' . $request->request->get("orderId"));
        }
        return view('card/index')->with(
            [
                'tokenApiKey' => Config::get('sample_setting.token.token_api_key'),
                'amount' => $request->request->get("amount")
            ]);
*/
        }
        return $mstatus;
    }

    // カード決済金額変更
    public function cardReAuthorize(
        $reservation,
        $request,
        $amount,
        $jpo1,
        $jpo2,
        $count_old_member,
        $count_requested_member
    ) {
        $logger = Log::channel('tgmdk')->getLogger();
        if ($logger instanceof LoggerInterface) {
            TGMDK_Logger::setLogger($logger);
        }
        if ($reservation->order_id != $reservation->number) {
            // ダミー予約を追加
            $dummy_reservation = new Reservation();
            $dummy_reservation->order_id = $reservation->order_id;
            $dummy_reservation->price_type = $reservation->price_type;
            $dummy_reservation->add_name_first = 'dummy';
            $dummy_reservation->add_name_last = 'dummy';
            $dummy_reservation->add_kana_first = 'dummy';
            $dummy_reservation->add_kana_last = 'dummy';
            $dummy_reservation->add_age = $reservation->add_age;
            $dummy_reservation->add_birth = $reservation->add_birth;
            $dummy_reservation->add_postalcode = $reservation->add_postalcode;
            $dummy_reservation->add_prefecture = $reservation->add_prefecture;
            $dummy_reservation->add_address = 'dummy';
            $dummy_reservation->add_telephone = 'dummy';
            $dummy_reservation->add_building =    'dummy';
            $dummy_reservation->add_boarding = 'dummy';
            $dummy_reservation->add_drop = $reservation->add_drop;
            $dummy_reservation->companion_name_first  = 'dummy';
            $dummy_reservation->companion_name_last   = 'dummy';
            $dummy_reservation->companion_kana_first  = 'dummy';
            $dummy_reservation->companion_kana_last   = 'dummy';
            $dummy_reservation->companion_age         = $reservation->companion_age;
            $dummy_reservation->companion_gender      = $reservation->companion_gender;
            $dummy_reservation->companion_birth       = $reservation->companion_birth;
            $dummy_reservation->companion_boarding    = 'dummy';
            $dummy_reservation->companion_drop        = $reservation->companion_drop;
            $dummy_reservation->status = 'dummy';
            $dummy_reservation->activity_date = 'dummy';
            $dummy_reservation->Number_of_reservations = $reservation->Number_of_reservations;
            $dummy_reservation->save();
        }
        // 新予約番号作成
        $new_order_id = $this->getNewOrderIdByDate(Carbon::today());
        Log::info('new_order_id is : ' . $new_order_id);
        //
        $request_data = new CardReAuthorizeRequestDto();
        $request_data->setOrderId($new_order_id);
        $request_data->setOriginalOrderId($reservation->number);
        $request_data->setAmount($amount);
        $request_data->setWithCapture('true');
        $request_data->setJpo(Helpers::generateJpo($jpo1, $jpo2));

        /*
         * 設定ファイルのパスを手動で指定する場合は以下のようにパスを指定してTGMDK_Configクラスのインスタンス生成をしておく
         * TGMDK_Config::getInstance("/home/test/laravel_sample/config/3GPSMDK.properties");
         */
        TGMDK_Config::getInstance();
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        $response_data = $transaction->execute($request_data);
        if ($response_data instanceof CardReAuthorizeResponseDto) {
            $mstatus = $response_data->getMstatus();
            $cancel_status = $this->cardCancel($reservation->order_id, $reservation->id, null);
            // DBの予約番号を上書き
            $reservation->order_id = $new_order_id;
            $reservation->save();
            $dt = new Carbon($reservation->fixed_datetime);
            $week_map = [
                0 => 'sunday',
                1 => 'monday',
                2 => 'tuesday',
                3 => 'wednesday',
                4 => 'thursday',
                5 => 'friday',
                6 => 'saturday',
            ];
            $day_of_week = $dt->dayOfWeek;
            $weekday = $week_map[$day_of_week];
            // 祝日判定
            $holidays = Yasumi::create('Japan', $dt->format('Y'));
            foreach ($holidays->getHolidayDates() as $holiday) {
                if ($holiday == $dt->format('Y-m-d')) {
                    $weekday = 'holiday';
                }
            }
            // 料金区分２０以上対応
            $typeid=0;
            $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
            if(!is_null($reservation->Number_of_reservations)){
                $Number_of_reservations = json_decode($reservation->Number_of_reservations);
                $count_member = 0;
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                                $typeid = $i;
                                $count_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                            }
                        }
                    }
                }
            }
            $prices = Price::select()
            ->where('plan_id' , $reservation->plan_id)
            ->where('type' , $typeid)
            ->get();
            $priceName = PriceType::select()
                ->where('number' , $typeid)
                ->first();
            // 予約者へメール通知
            Mail::send(
                ['text' => 'user.reservations.email'],
                [
                    'number' => $reservation->order_id,
                    'plan' => $reservation->plan->name,
                    'date' => date(
                        'Y年m月d日',
                        strtotime($reservation->fixed_datetime)
                    ),
                    'activity' => $reservation->activity_date,
                    'name_last' => $reservation->user->name_last,
                    'name_first' => $reservation->user->name_first,
                    'email' => $reservation->user->email,
                    'tel' => $reservation->user->tel,
                    'tel2' => $reservation->user->tel2,
                    'reservation' => $reservation,
                    'payment' => 'クレジットカード決済',
                    'haraikomiUrl' => null,
                    'weekday' => $weekday,
                    'amount' => $amount,
                    'receiptNo' => null,
                    'prices' => $prices,
                    'priceName' => $priceName,
                    'type_id' => $typeid
                ],
                function ($message) use ($reservation) {
                    if ($reservation->user->email) {
                        $message
                            ->to($reservation->user->email)
                            ->bcc([config('mail.custom.addresses.main')])
                            ->from(config('mail.custom.addresses.main'))
                            ->subject('【長野電鉄株式会社】決済金額変更メール');
                    }
                }
            );
            /*
            // ベリトランスオーダーIDをDBへ格納
            $reservation->order_id = $request->request->get("orderId");
            // 決済ステータスをDBへ格納
            $reservation->status = '予約確定';
            $reservation->save();
*/
            // 在庫を戻して、再度引き直し
            $stock = Stock::select()
                ->where('plan_id', $reservation->plan->id)
                ->where(
                    'res_date',
                    date('Y-m-d', strtotime($reservation->fixed_datetime))
                )
                ->first();
            if ($stock) {
                if ($reservation->plan->res_limit_flag == 0) {
                    // 予約人数をカウント
                    $stock->limit_number =
                        $stock->limit_number + $count_old_member;
                    $stock->limit_number =
                        $stock->limit_number - $count_requested_member;
                    $stock->save();
                } else {
                    $stock->limit_number = $stock->limit_number + 1;
                    $stock->save();
                }
            }
            return $mstatus;
        }
        /*
        return view('card/index')->with(
            [
                'tokenApiKey' => Config::get('sample_setting.token.token_api_key'),
                'amount' => $request->request->get("amount")
            ]);
        }
*/
        return $mstatus;
    }
    // 決済メールプレビュー
    public function PreviewPaymentMail($id)
    {
        ini_set('memory_limit', '256M');
        $reservation = Reservation::find($id);
        /*if ($reservation->status != 'リクエスト予約') {
            throw ValidationException::withMessages([
                'status_error' =>
                    '予約ステータスをリクエスト予約に変更後、再度お試しください',
            ]);
        }*/

        $prices = Price::select()
        ->where('plan_id' , $reservation->plan_id)
        ->where('type' , $reservation->price_type)
        ->get();
        $priceName = PriceType::select()
                    ->where('number' , $reservation->price_type)
                    ->first();
        // 料金区分２０以上対応
        $typeid=0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            $count_old_member = 0;
            $typeid = 0;
            for($i=0;$i<=100;$i++){
                for($j=0;$j<count($byDay);$j++){
                    for($k=1;$k<=3;$k++){
                        if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                            $typeid = $i;
                            $prtype = $j;
                            $count_old_member += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)};
                        }
                    }
                }
            }
        }
        $prices = Price::select()
                    ->where('plan_id' , $reservation->plan_id)
                    ->where('type' , $typeid)
                    ->get();
        $priceName = PriceType::select()
                        ->where('number' , $typeid)
                        ->first();
        //$reservation->save();
        // 合計金額セット
        $amount = 0;
        $dt = new Carbon($reservation->fixed_datetime);
        $week_map = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $day_of_week = $dt->dayOfWeek;
        $weekday = $week_map[$day_of_week];
        // 祝日判定
        $holidays = Yasumi::create('Japan', $dt->format('Y'));
        foreach ($holidays->getHolidayDates() as $holiday) {
            if ($holiday == $dt->format('Y-m-d')) {
                $weekday = 'holiday';
            }
        }
        // 料金区分２０以上対応
        // 合計金額リセット
        $amount = 0;
        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
        if(!is_null($reservation->Number_of_reservations)){
            $Number_of_reservations = json_decode($reservation->Number_of_reservations);
            foreach ($prices as $price) {
                for($i=0;$i<=100;$i++){
                    for($j=0;$j<count($byDay);$j++){
                        for($k=1;$k<=3;$k++){
                            if(array_key_exists(sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k),$Number_of_reservations)){
                                $amount += $Number_of_reservations->{sprintf('type%d_%s_%d_number', $i,$byDay[$j],$k)} * $price->{sprintf('%s_%d', $byDay[$j],$k)};
                            }
                        }
                    }
                }
            }
            if(array_key_exists('custom_flg', $Number_of_reservations)){
                if($Number_of_reservations->custom_flg == 1){
                    if(property_exists($Number_of_reservations,'typec_price')){
                        $amount = 0;
                        for($j=1;$j<=20;$j++){
                            $amount += $Number_of_reservations->typec_price->{$j} * $Number_of_reservations->typec_number->{$j};
                        }
                    }else{
                        throw ValidationException::withMessages([
                            'custom_error' =>
                                '「変更する」ボタンクリック後、再度お試しください',
                        ]);
                    }
                }
            }
        }
        // 決済用URLメール通知
        // パラメーターを暗号化
        $param_card = [
            'payment_method' => 0,
            'reservation_id' => $reservation->id,
        ];
        $param_cvs = [
            'payment_method' => 1,
            'reservation_id' => $reservation->id,
        ];
        $bank = Bankaccount::find($reservation->plan->company->id);
        // 各項目設定
        $number = $reservation->order_id;
        $plan = $reservation->plan->name;
        $date = date('Y年m月d日',strtotime($reservation->fixed_datetime));
        $payment_limit = Helpers::calcPaymentDeadline(Carbon::today(), $reservation->plan->payment_plus_day, $reservation->plan->payment_final_deadline);

        $activity      = $reservation->activity_date;
        $name_last     = $reservation->user->name_last;
        $kana_last     = $reservation->user->kana_last;
        $name_first    = $reservation->user->name_first;
        $kana_first    = $reservation->user->kana_first;
        $postalcode    = $reservation->user->postalcode;
        $prefecture    = $reservation->user->prefecture;
        $address       = $reservation->user->address;
        $birth_year    = $reservation->user->birth_year;
        $birth_month   = $reservation->user->birth_month;
        $birth_day     = $reservation->user->birth_day;
        $email         = $reservation->user->email;
        $tel           = $reservation->user->tel;
        $tel2          = $reservation->user->tel2;
        $reservation   = $reservation;
        $amount        = $amount;
        $weekday       = $weekday;
        $bank          = $bank;
        $prices        = $prices;
        $priceName     = $priceName;
        $type_id   = $typeid;

        switch ($reservation->payment_method) {
            case PaymentMethodConstants::PREPAY:
                $payment = '銀行振込';
                return view(
                    'user.reservations.prepayemail_pv',
                    compact('number', 'plan', 'date', 'activity', 'name_last', 'kana_last', 'name_first', 'kana_first', 'postalcode', 'prefecture', 'address', 'birth_year', 'birth_month', 'birth_day',
                        'email', 'tel', 'tel2', 'reservation', 'amount', 'weekday', 'bank', 'payment', 'prices', 'priceName', 'type_id', 'payment_limit'
                    )
                );
            case PaymentMethodConstants::CVS:
                $url_cvs = secure_url('pay?prm='.encrypt($param_cvs));
                return view(
                    'user.reservations.cvsemail_pv',
                    compact('url_cvs', 'number', 'plan', 'date', 'activity', 'name_last', 'name_first',
                        'email', 'tel', 'tel2', 'reservation', 'amount', 'weekday', 'prices', 'priceName', 'type_id', 'payment_limit'
                    )
                );
            case PaymentMethodConstants::CARD:
                $url_card = secure_url('pay?prm='.encrypt($param_card));
                return view(
                    'user.reservations.cardemail_pv',
                    compact('url_card', 'number', 'plan', 'date', 'activity', 'name_last', 'name_first',
                        'email', 'tel', 'tel2', 'reservation', 'amount', 'weekday', 'prices', 'priceName', 'type_id', 'payment_limit'
                    )
                );
            case PaymentMethodConstants::SPOT:
                $payment = '現地払い';
                return view(
                    'user.reservations.spotemail_pv',
                    compact('number', 'plan', 'date', 'activity', 'name_last', 'kana_last', 'name_first', 'kana_first', 'postalcode', 'prefecture', 'address', 'birth_year', 'birth_month', 'birth_day',
                        'email', 'tel', 'tel2', 'reservation', 'amount', 'weekday', 'bank', 'payment', 'prices', 'priceName', 'type_id', 'payment_limit'
                    )
                );
            default:
                throw new LogicException();
        }
    }

    /**
     * @param  DateTimeInterface  $date
     * @return string
     */
    private function getNewOrderIdByDate(DateTimeInterface $date): string
    {
        $code = config('setting.order_id_prefix').$date->format('Ymd');
        return $code.str_pad($this->increaseOrderId($code), 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param  string  $code
     * @return int
     */
    private function increaseOrderId(string $code): int
    {
        $callable = function () use ($code) {
            $sequence = Sequence::where('code', $code)->lockForUpdate()->first(['id', 'seq']);
            if (!$sequence) {
                $sequence = new Sequence;
                $sequence->code = $code;
                $sequence->seq = -1;
            }
            $sequence->seq += 1;
            $sequence->save();
            return $sequence->seq;
        };
        return DB::transactionLevel() ? $callable() : DB::transaction($callable);
    }
}
