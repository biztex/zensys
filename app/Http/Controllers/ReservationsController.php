<?php

namespace App\Http\Controllers;

use InterventionImage;
use App\Models\Reservation;
use App\Models\Genre;
use App\Models\Activity;
use App\Models\Price;
use App\Models\Plan;
use App\Models\User;
use App\Models\Stock;
use App\Models\Bankaccount;
use App\Models\PriceType;
use App\Models\StockPriceType;
use App\Helpers;
use Auth;
use Yasumi\Yasumi;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Psr\Log\LoggerInterface;
use tgMdk\dto\CardAuthorizeRequestDto;
use tgMdk\dto\CardAuthorizeResponseDto;
use tgMdk\dto\CardReAuthorizeRequestDto;
use tgMdk\dto\CardReAuthorizeResponseDto;
use tgMdk\dto\CardCancelRequestDto;
use tgMdk\dto\CardCancelResponseDto;
use tgMdk\dto\CvsCancelRequestDto;
use tgMdk\dto\CvsCancelResponseDto;
use tgMdk\TGMDK_Config;
use tgMdk\TGMDK_Logger;
use tgMdk\TGMDK_Transaction;
use App\Models\Company;

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

        $pieces = explode(',',$plan->boarding);
        $drops = explode(',',$plan->drop);

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
                'answer' => ['required', 'string', 'max:500'],
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
        $price_name = PriceType::select()
                        ->where('number' , $request->price_type)->first();
       
        $count = Reservation::whereDate('created_at', Carbon::today())->count();
        $date = date('Ymd');
        $zeropadding = sprintf('%05d', $count);
        $reservation_number = 'ZR' . $date . $zeropadding;
        // 予約追加
        $reservation = new Reservation();
        $reservation->plan_id = $request->plan_id;
        $reservation->user_id = $user->id;
        $reservation->order_id = $reservation_number;
        $reservation->number = $reservation_number;
        $reservation->activity_date = $request->selected_activity;
        $reservation->fixed_datetime = $request->date;
        $reservation->answer = $request->answer;
        $reservation->price_type = $request->price_type;

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
        $reservation->save();
        // 決済情報をセット
        //$tokenApiKey = Config::get('sample_setting.token.token_api_key');
        $tokenApiKey = '1493ffc3-323d-470c-b617-c116e639f6b1';
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
            foreach ($plan->prices as $price) {
                if ($reservation->type0_number && $price->type == 0) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type0_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type0_number * $price->{$weekday};
                    }
                } elseif ($reservation->type1_number && $price->type == 1) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type1_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type1_number * $price->{$weekday};
                    }
                } elseif ($reservation->type2_number && $price->type == 2) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type2_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type2_number * $price->{$weekday};
                    }
                } elseif ($reservation->type3_number && $price->type == 3) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type3_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type3_number * $price->{$weekday};
                    }
                } elseif ($reservation->type4_number && $price->type == 4) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type4_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type4_number * $price->{$weekday};
                    }
                } elseif ($reservation->type5_number && $price->type == 5) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type5_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type5_number * $price->{$weekday};
                    }
                } elseif ($reservation->type6_number && $price->type == 6) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type6_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type6_number * $price->{$weekday};
                    }
                } elseif ($reservation->type7_number && $price->type == 7) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type7_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type7_number * $price->{$weekday};
                    }
                } elseif ($reservation->type8_number && $price->type == 8) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type8_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type8_number * $price->{$weekday};
                    }
                } elseif ($reservation->type9_number && $price->type == 9) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type9_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type9_number * $price->{$weekday};
                    }
                } elseif ($reservation->type10_number && $price->type == 10) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type10_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type10_number * $price->{$weekday};
                    }
                } elseif ($reservation->type11_number && $price->type == 11) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type11_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type11_number * $price->{$weekday};
                    }
                } elseif ($reservation->type12_number && $price->type == 12) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type12_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type12_number * $price->{$weekday};
                    }
                } elseif ($reservation->type13_number && $price->type == 13) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type13_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type13_number * $price->{$weekday};
                    }
                } elseif ($reservation->type14_number && $price->type == 14) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type14_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type14_number * $price->{$weekday};
                    }
                } elseif ($reservation->type15_number && $price->type == 15) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type15_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type15_number * $price->{$weekday};
                    }
                } elseif ($reservation->type16_number && $price->type == 16) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type16_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type16_number * $price->{$weekday};
                    }
                } elseif ($reservation->type17_number && $price->type == 17) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type17_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type17_number * $price->{$weekday};
                    }
                } elseif ($reservation->type18_number && $price->type == 18) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type18_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type18_number * $price->{$weekday};
                    }
                } elseif ($reservation->type19_number && $price->type == 19) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type19_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type19_number * $price->{$weekday};
                    }
                } elseif ($reservation->type20_number && $price->type == 20) {
                    if ($price->week_flag == 0) {
                        $amount += $reservation->type20_number * $price->price;
                    } else {
                        $amount +=
                            $reservation->type20_number * $price->{$weekday};
                    }
                }
            }
        }

        //$tokenApiKey = 'cd76ca65-7f54-4dec-8ba3-11c12e36a548';
        $tokenApiKey = '1493ffc3-323d-470c-b617-c116e639f6b1';
        $orderId = $reservation_number;
        // 管理者へ通知メール
        if ($request->is_request == 0) {
            Mail::send(
                ['text' => 'user.reservations.clientemail'],
                [
                    'number'        => $reservation->order_id,
                    'plan'          => $plan->name,
                    'date'          => date('Y年m月d日', strtotime($request->date)),
                    'activity'      => $request->selected_activity,
                    'name_last'     => $request->name_last,
                    'name_first'    => $request->name_first,
                    'email'         => $request->email,
                    'tel'           => $request->tel,
                    'tel2'          => $request->tel2,
                    'kana_last'     => $request->kana_last,
                    'kana_first'    => $request->kana_first,
                    'postalcode'    => $request->postalcode,
                    'prefecture'    => $request->prefecture,
                    'address'       => $request->address,
                    'brithday'      => $request->birth_day,
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($request) {
                    if ($request->email) {
                        $message
                            //->to('clgeneral015@gmail.com')
                            ->to('info@zenryo-ec.com')
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】本予約が入りました');
                    }
                }
            );
        } else {
            Mail::send(
                ['text' => 'user.reservations.clientemail'],
                [
                    'number'        => $reservation->order_id,
                    'plan'          => $plan->name,
                    'date'          => date('Y年m月d日', strtotime($request->date)),
                    'activity'      => $request->selected_activity,
                    'name_last'     => $request->name_last,
                    'name_first'    => $request->name_first,
                    'email'         => $request->email,
                    'tel'           => $request->tel,
                    'tel2'          => $request->tel2,
                    'kana_last'     => $request->kana_last,
                    'kana_first'    => $request->kana_first,
                    'postalcode'    => $request->postalcode,
                    'prefecture'    => $request->prefecture,
                    'address'       => $request->address,
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($request) {
                    if ($request->email) {
                        $message
                            //->to('clgeneral015@gmail.com')
                            ->to('info@zenryo-ec.com')
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】リクエスト予約が入りました');
                    }
                }
            );
        }

        // ここまで
        if ($request->is_request == 0) {
            if ($reservation->payment_method == 3) {
                return view(
                    'card.index',
                    compact(
                        'reservation',
                        'amount',
                        'user',
                        'tokenApiKey',
                        'orderId',
                        'weekday'
                    )
                );
            } elseif ($reservation->payment_method == 2) {
                return view(
                    'cvs.index',
                    compact(
                        'reservation',
                        'amount',
                        'user',
                        'tokenApiKey',
                        'orderId',
                        'weekday'
                    )
                );
            } elseif ($reservation->payment_method == 1) {
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
                        'name_last' => $request->user->name_last,
                        'name_first' => $request->user->name_first,
                        'email' => $request->user->email,
                        'tel' => $request->user->tel,
                        'tel2' => $request->user->tel2,
                        'kana_last' => $request->kana_last,
                        'kana_first' => $request->kana_first,
                        'postalcode' => $request->postalcode,
                        'prefecture' => $request->prefecture,
                        'address' => $request->address,
                        'birth_day' => $request->birth_day,
                        'reservation' => $reservation,
                        'payment' => '事前払い',
                        'haraikomiUrl' => null,
                        'amount' => $amount,
                        'weekday' => $weekday,
                        'bank' => $bank,
                        'prices'        => $prices,
                    'priceName'     => $priceName
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->from('info@zenryo-ec.com')
                                ->subject('【全旅】予約確定メール');
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
                        // 予約人数をカウント
                        $count_member = 0;
                        for ($i = 0; $i <= 20; $i++) {
                            $count = $reservation->{'type' . $i . '_number'};
                            if ($count > 0) {
                                $count_member += $count;
                            }
                        }
                        $stock->limit_number =
                            $stock->limit_number - $count_member;
                        $stock->save();
                    } else {
                        $stock->limit_number = $stock->limit_number - 1;
                        $stock->save();
                    }
                }
                return view('user.reservations.result');
            } elseif ($reservation->payment_method == 0) {
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
                        'birth_day' => $request->birth_day,
                        'reservation' => $reservation,
                        'payment' => '現地払い',
                        'haraikomiUrl' => null,
                        'weekday' => $weekday,
                        'amount' => $amount,
                        'prices'        => $prices,
                    'priceName'     => $priceName
                    ],
                    function ($message) use ($reservation) {
                        if ($reservation->user->email) {
                            $message
                                ->to($reservation->user->email)
                                ->from('info@zenryo-ec.com')
                                ->subject('【全旅】予約確定メール');
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
                        // 予約人数をカウント
                        $count_member = 0;
                        for ($i = 0; $i <= 20; $i++) {
                            $count = $reservation->{'type' . $i . '_number'};
                            if ($count > 0) {
                                $count_member += $count;
                            }
                        }
                        $stock->limit_number =
                            $stock->limit_number - $count_member;
                        $stock->save();
                    } else {
                        $stock->limit_number = $stock->limit_number - 1;
                        $stock->save();
                    }
                }
                return view('user.reservations.result');
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
                    'reservation' => $reservation,
                    'weekday' => $weekday,
                    'amount' => $amount,
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($request) {
                    if ($request->email) {
                        $message
                            ->to($request->email)
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】リクエスト予約確認メール');
                    }
                }
            );
            return view('user.reservations.reqresult');
        }
    }

    // 作成処理
    public function confirmForUser(Request $request)
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
                'answer' => ['required', 'string', 'max:500'],
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
        $prices = $request->price_table ;
        // $types = [];
        // foreach ($request->type_number as $type) {
        //     # code...
        //     $types = $type;
        // }

        $types = $request->type_number ;

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
        $info['payment_method']             = $request->payment_method;
        $info['answer']                     = $request->answer;
        $info['price_type']                 = $request->price_type;

        return view(
            'user.reservations.confirm',
            compact(
                'plan',
                'companies',
                'info',
                'prices',
                'types',
                'date',
                'weekday',
                'stock',
                'stock_price_types',
                'request'
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
            ->orderBy('id')
            ->get();
        $genres = Genre::all();
        $dt = new Carbon($reservations->fixed_datetime);
        $prices = Price::select()
                    ->where('plan_id' , $reservations->plan_id)
                    ->where('type' , $reservations->price_type)
                    ->get();
        $priceName = PriceType::select()
                    ->where('number' , $reservations->price_type)
                    ->first();

                   


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
        return view(
            'client.reservations.edit',
            compact('reservations', 'categories', 'genres', 'weekday','prices','priceName')
        );
    }

    // 決済メール送信のみ
    public function sendPaymentMail(Request $request, $id)
    {
        ini_set('memory_limit', '256M');
        $rules = [
            'status' => ['required', 'string', 'max:7'],
            'type0_number' => ['nullable', 'numeric', 'max:99'],
            'type1_number' => ['nullable', 'numeric', 'max:99'],
            'type2_number' => ['nullable', 'numeric', 'max:99'],
            'type3_number' => ['nullable', 'numeric', 'max:99'],
            'type4_number' => ['nullable', 'numeric', 'max:99'],
            'type5_number' => ['nullable', 'numeric', 'max:99'],
            'type6_number' => ['nullable', 'numeric', 'max:99'],
            'type7_number' => ['nullable', 'numeric', 'max:99'],
            'type8_number' => ['nullable', 'numeric', 'max:99'],
            'type9_number' => ['nullable', 'numeric', 'max:99'],
            'type10_number' => ['nullable', 'numeric', 'max:99'],
            'type11_number' => ['nullable', 'numeric', 'max:99'],
            'type12_number' => ['nullable', 'numeric', 'max:99'],
            'type13_number' => ['nullable', 'numeric', 'max:99'],
            'type14_number' => ['nullable', 'numeric', 'max:99'],
            'type15_number' => ['nullable', 'numeric', 'max:99'],
            'type16_number' => ['nullable', 'numeric', 'max:99'],
            'type17_number' => ['nullable', 'numeric', 'max:99'],
            'type18_number' => ['nullable', 'numeric', 'max:99'],
            'type19_number' => ['nullable', 'numeric', 'max:99'],
            'type20_number' => ['nullable', 'numeric', 'max:99'],
            'memo' => ['nullable', 'string', 'max:200'],
        ];
        $this->validate($request, $rules);
        for ($i = 0; $i < 6; $i++) {
            if (!$request->{'type' . $i . '_number'}) {
                $request->{'type' . $i . '_number'} = 0;
            }
        }
        $reservation = Reservation::find($id);
        if ($reservation->status != 'リクエスト予約') {
            throw ValidationException::withMessages([
                'status_error' =>
                    '予約ステータスをリクエスト予約に変更後、再度送信をお試しください',
            ]);
        }

        $prices = Price::select()
        ->where('plan_id' , $reservation->plan_id)
        ->where('type' , $reservation->price_type)
        ->get();
        $priceName = PriceType::select()
                    ->where('number' , $reservation->price_type)
                    ->first();
        $reservation->status = $request->status;
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
        foreach ($reservation->plan->prices as $price) {
            if ($reservation->type0_number && $price->type == 0) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type0_number * $price->price;
                } else {
                    $amount += $reservation->type0_number * $price->{$weekday};
                }
            } elseif ($reservation->type1_number && $price->type == 1) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type1_number * $price->price;
                } else {
                    $amount += $reservation->type1_number * $price->{$weekday};
                }
            } elseif ($reservation->type2_number && $price->type == 2) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type2_number * $price->price;
                } else {
                    $amount += $reservation->type2_number * $price->{$weekday};
                }
            } elseif ($reservation->type3_number && $price->type == 3) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type3_number * $price->price;
                } else {
                    $amount += $reservation->type3_number * $price->{$weekday};
                }
            } elseif ($reservation->type4_number && $price->type == 4) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type4_number * $price->price;
                } else {
                    $amount += $reservation->type4_number * $price->{$weekday};
                }
            } elseif ($reservation->type5_number && $price->type == 5) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type5_number * $price->price;
                } else {
                    $amount += $reservation->type5_number * $price->{$weekday};
                }
            } elseif ($reservation->type6_number && $price->type == 6) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type6_number * $price->price;
                } else {
                    $amount += $reservation->type6_number * $price->{$weekday};
                }
            } elseif ($reservation->type7_number && $price->type == 7) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type7_number * $price->price;
                } else {
                    $amount += $reservation->type7_number * $price->{$weekday};
                }
            } elseif ($reservation->type8_number && $price->type == 8) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type8_number * $price->price;
                } else {
                    $amount += $reservation->type8_number * $price->{$weekday};
                }
            } elseif ($reservation->type9_number && $price->type == 9) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type9_number * $price->price;
                } else {
                    $amount += $reservation->type9_number * $price->{$weekday};
                }
            } elseif ($reservation->type10_number && $price->type == 10) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type10_number * $price->price;
                } else {
                    $amount += $reservation->type10_number * $price->{$weekday};
                }
            } elseif ($reservation->type11_number && $price->type == 11) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type11_number * $price->price;
                } else {
                    $amount += $reservation->type11_number * $price->{$weekday};
                }
            } elseif ($reservation->type12_number && $price->type == 12) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type12_number * $price->price;
                } else {
                    $amount += $reservation->type12_number * $price->{$weekday};
                }
            } elseif ($reservation->type13_number && $price->type == 13) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type13_number * $price->price;
                } else {
                    $amount += $reservation->type13_number * $price->{$weekday};
                }
            } elseif ($reservation->type14_number && $price->type == 14) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type14_number * $price->price;
                } else {
                    $amount += $reservation->type14_number * $price->{$weekday};
                }
            } elseif ($reservation->type15_number && $price->type == 15) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type15_number * $price->price;
                } else {
                    $amount += $reservation->type15_number * $price->{$weekday};
                }
            } elseif ($reservation->type16_number && $price->type == 16) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type16_number * $price->price;
                } else {
                    $amount += $reservation->type16_number * $price->{$weekday};
                }
            } elseif ($reservation->type17_number && $price->type == 17) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type17_number * $price->price;
                } else {
                    $amount += $reservation->type17_number * $price->{$weekday};
                }
            } elseif ($reservation->type18_number && $price->type == 18) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type18_number * $price->price;
                } else {
                    $amount += $reservation->type18_number * $price->{$weekday};
                }
            } elseif ($reservation->type19_number && $price->type == 19) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type19_number * $price->price;
                } else {
                    $amount += $reservation->type19_number * $price->{$weekday};
                }
            } elseif ($reservation->type20_number && $price->type == 20) {
                if ($price->week_flag == 0) {
                    $amount += $reservation->type20_number * $price->price;
                } else {
                    $amount += $reservation->type20_number * $price->{$weekday};
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
        // 決済方法ごとにメールテンプレートを分岐
        $pm = $reservation->payment_method;
        if ($pm == 0) {
            // 現地前払い
            Mail::send(
                ['text' => 'user.reservations.spotemail'],
                [
                    'number' => $reservation->order_id,
                    'plan' => $reservation->plan->name,
                    'date' => date(
                        'Y年m月d日',
                        strtotime($reservation->fixed_datetime)
                    ),
                    'activity'      => $reservation->activity_date,
                    'name_last'     => $reservation->user->name_last,
                    'kana_last'     => $reservation->user->kana_last,
                    'name_first'    => $reservation->user->name_first,
                    'kana_first'    => $reservation->user->kana_first,
                    'postalcode'    => $reservation->user->postalcode,
                    'prefecture'    => $reservation->user->prefecture,
                    'address'       => $reservation->user->address,
                    'birth_year'    => $reservation->user->birth_year,
                    'birth_month'   => $reservation->user->birth_month,
                    'birth_day'     => $reservation->user->birth_day,


                    'email'         => $reservation->user->email,
                    'tel'           => $reservation->user->tel,
                    'tel2'          => $reservation->user->tel2,
                    'reservation'   => $reservation,
                    'amount'        => $amount,
                    'weekday'       => $weekday,
                    'bank'          => $bank,
                    'payment'       => '現地払い',
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($reservation) {
                    if ($reservation->user->email) {
                        $message
                            ->to($reservation->user->email)
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】予約確定通知メール');
                    }
                }
            );
        } elseif ($pm == 1) {
            // 事前払い
            Mail::send(
                ['text' => 'user.reservations.prepayemail'],
                [
                    'number' => $reservation->order_id,
                    'plan' => $reservation->plan->name,
                    'date' => date(
                        'Y年m月d日',
                        strtotime($reservation->fixed_datetime)
                    ),
                    'activity'      => $reservation->activity_date,
                    'name_last'     => $reservation->user->name_last,
                    'kana_last'     => $reservation->user->kana_last,
                    'name_first'    => $reservation->user->name_first,
                    'kana_first'    => $reservation->user->kana_first,
                    'postalcode'    => $reservation->user->postalcode,
                    'prefecture'    => $reservation->user->prefecture,
                    'address'       => $reservation->user->address,
                    'email'         => $reservation->user->email,
                    'tel'           => $reservation->user->tel,
                    'tel2'          => $reservation->user->tel2,
                    'birth_year'    => $reservation->user->birth_year,
                    'birth_month'   => $reservation->user->birth_month,
                    'birth_day'     => $reservation->user->birth_day,
                    'reservation'   => $reservation,
                    'amount'        => $amount,
                    'weekday'       => $weekday,
                    'bank'          => $bank,
                    'payment'       => '事前払い',
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($reservation) {
                    if ($reservation->user->email) {
                        $message
                            ->to($reservation->user->email)
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】予約確定通知メール');
                    }
                }
            );
        } elseif ($pm == 2) {
            Mail::send(
                ['text' => 'user.reservations.cvsemail'],
                [
                    'url_cvs' =>
                        'https://blue-tourism-hokkaido.website/pay?prm=' .
                        encrypt($param_cvs),
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
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($reservation) {
                    if ($reservation->user->email) {
                        $message
                            ->to($reservation->user->email)
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】予約確定通知メール');
                    }
                }
            );
        } elseif ($pm == 3) {
            Mail::send(
                ['text' => 'user.reservations.cardemail'],
                [
                    'url_card' =>
                        'https://blue-tourism-hokkaido.website/pay?prm=' .
                        encrypt($param_card),
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
                    'prices'        => $prices,
                    'priceName'     => $priceName
                ],
                function ($message) use ($reservation) {
                    if ($reservation->user->email) {
                        $message
                            ->to($reservation->user->email)
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】予約確定通知メール');
                    }
                }
            );
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
        // 人数変更＝価格変更があった場合
        $count_old_member = 0;
        for ($i = 0; $i <= 20; $i++) {
            $num1 = $reservation->{'type' . $i . '_number'};
            if ($num1 > 0) {
                $count_old_member += $num1;
            }
        }
        $count_requested_member = 0;
        for ($i = 0; $i <= 20; $i++) {
            $num2 = $request->{'type' . $i . '_number'};
            if ($num2 > 0) {
                $count_requested_member += $num2;
            }
        }
        $reservation->status = $request->status;
        $reservation->payment_method = $request->payment_method;
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
            foreach ($reservation->plan->prices as $price) {
                if ($request->type0_number && $price->type == 0) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type0_number * $price->price;
                    } else {
                        $amount += $request->type0_number * $price->{$weekday};
                    }
                } elseif ($request->type1_number && $price->type == 1) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type1_number * $price->price;
                    } else {
                        $amount += $request->type1_number * $price->{$weekday};
                    }
                } elseif ($request->type2_number && $price->type == 2) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type2_number * $price->price;
                    } else {
                        $amount += $request->type2_number * $price->{$weekday};
                    }
                } elseif ($request->type3_number && $price->type == 3) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type3_number * $price->price;
                    } else {
                        $amount += $request->type3_number * $price->{$weekday};
                    }
                } elseif ($request->type4_number && $price->type == 4) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type4_number * $price->price;
                    } else {
                        $amount += $request->type4_number * $price->{$weekday};
                    }
                } elseif ($request->type5_number && $price->type == 5) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type5_number * $price->price;
                    } else {
                        $amount += $request->type5_number * $price->{$weekday};
                    }
                } elseif ($request->type6_number && $price->type == 6) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type6_number * $price->price;
                    } else {
                        $amount += $request->type6_number * $price->{$weekday};
                    }
                } elseif ($request->type7_number && $price->type == 7) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type7_number * $price->price;
                    } else {
                        $amount += $request->type7_number * $price->{$weekday};
                    }
                } elseif ($request->type8_number && $price->type == 8) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type8_number * $price->price;
                    } else {
                        $amount += $request->type8_number * $price->{$weekday};
                    }
                } elseif ($request->type9_number && $price->type == 9) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type9_number * $price->price;
                    } else {
                        $amount += $request->type9_number * $price->{$weekday};
                    }
                } elseif ($request->type10_number && $price->type == 10) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type10_number * $price->price;
                    } else {
                        $amount += $request->type10_number * $price->{$weekday};
                    }
                } elseif ($request->type11_number && $price->type == 11) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type11_number * $price->price;
                    } else {
                        $amount += $request->type11_number * $price->{$weekday};
                    }
                } elseif ($request->type12_number && $price->type == 12) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type12_number * $price->price;
                    } else {
                        $amount += $request->type12_number * $price->{$weekday};
                    }
                } elseif ($request->type13_number && $price->type == 13) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type13_number * $price->price;
                    } else {
                        $amount += $request->type13_number * $price->{$weekday};
                    }
                } elseif ($request->type14_number && $price->type == 14) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type14_number * $price->price;
                    } else {
                        $amount += $request->type14_number * $price->{$weekday};
                    }
                } elseif ($request->type15_number && $price->type == 15) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type15_number * $price->price;
                    } else {
                        $amount += $request->type15_number * $price->{$weekday};
                    }
                } elseif ($request->type16_number && $price->type == 16) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type16_number * $price->price;
                    } else {
                        $amount += $request->type16_number * $price->{$weekday};
                    }
                } elseif ($request->type17_number && $price->type == 17) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type17_number * $price->price;
                    } else {
                        $amount += $request->type17_number * $price->{$weekday};
                    }
                } elseif ($request->type18_number && $price->type == 18) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type18_number * $price->price;
                    } else {
                        $amount += $request->type18_number * $price->{$weekday};
                    }
                } elseif ($request->type19_number && $price->type == 19) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type19_number * $price->price;
                    } else {
                        $amount += $request->type19_number * $price->{$weekday};
                    }
                } elseif ($request->type20_number && $price->type == 20) {
                    if ($price->week_flag == 0) {
                        $amount += $request->type20_number * $price->price;
                    } else {
                        $amount += $request->type20_number * $price->{$weekday};
                    }
                }
            }
            if ($reservation->payment_method == 3) {
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
        $reservation->save();
        // コンビニ決済キャンセルの場合
        if (
            $request->status == 'キャンセル' &&
            $reservation->payment_method == 2 &&
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
            $reservation->payment_method == 3 &&
            $reservation->order_id
        ) {
            $mstatus = $this->cardCancel($reservation->order_id);
            if ($mstatus != 'success') {
                return redirect()
                    ->back()
                    ->withErrors([
                        'DGFT側のクレジットカード決済がキャンセルできませんでした。詳細はMAP管理画面で該当の予約番号をご確認ください',
                    ]);
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
        $reservations = Reservation::destroy($id);
        // リレーションレコード削除1
        Activity::where('plan_id', $id)->delete();
        // リレーションレコード削除2
        Price::where('plan_id', $id)->delete();
        return redirect()->back();
    }

    // 選択削除処理
    public function destroySelected(Request $request)
    {
        $ids = explode(',', $request->ids);
        $reservations = Reservation::destroy($ids);
        // リレーションレコード削除
        foreach ($ids as $id) {
            Activity::where('plan_id', $id)->delete();
            Price::where('plan_id', $id)->delete();
        }
        return redirect()->back();
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
        $fp = fopen('php://output', 'w');

        // UTF-8からSJIS-winへ変換するフィルター
        //stream_filter_append($fp, 'convert.iconv.UTF-8/CP932//TRANSLIT', STREAM_FILTER_WRITE);
        fputcsv(
            $fp,
            [
                'ID',
                'プラン名',
                '予約番号',
                '受付タイプ',
                '予約ステータス',
                '予約者への質問',
                '予約者からの回答',
                '支払方法',
                'その他 備考・特記事項',
            ],
            ',',
            '"'
        );
        foreach ($reservations as $reservation) {
            $row = [];
            $row[] = $reservation->id;
            $row[] = $reservation->plan->name;
            $row[] = $reservation->order_id;
            if ($reservation->plan->res_type == '0') {
                $row[] = '即時';
            } elseif ($reservation->plan->res_type == '1') {
                $row[] = '即時・リクエスト併用';
            } else {
                $row[] = 'リクエスト予約';
            }
            $row[] = $reservation->status;
            $row[] = $reservation->plan->question_content;
            $row[] = $reservation->answer;

            if ($reservation->payment_method == '0') {
                $row[] = '現地払い';
            } elseif ($reservation->payment_method == '1') {
                $row[] = '事前払い';
            } elseif ($reservation->payment_method == '2') {
                $row[] = '事前コンビニ決済';
            } elseif ($reservation->payment_method == '3') {
                $row[] = '事前クレジットカード決済';
            } else {
                $row[] = '';
            }
            $row[] = $reservation->memo;
            fputcsv($fp, $row, ',', '"');
        }
        fclose($fp);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=reservation.csv');
        header('Content-Transfer-Encoding: binary');
        exit();
    }

    // JSON返却
    public function json($user_id = null)
    {
        try {
            $query = null;
            if ($user_id == null) {
                $query = Reservation::with(['user', 'plan'])->where(
                    'number',
                    '!=',
                    null
                );
                if (isset($_GET['status']) && $_GET['status']) {
                    $query = $query->where('status', $_GET['status']);
                }
                if (isset($_GET['date']) && $_GET['date']) {
                    $query = $query->whereDate('created_at', $_GET['date']);
                }
                // if($_GET['status']){
                //     $query = $query->where('status', $_GET['status'])
                // }
                $query = $query->orderBy('id', 'desc');
            } else {
                $query = Reservation::with(['user', 'plan']);
                if (isset($_GET['status']) && $_GET['status']) {
                    $query = $query->where('status', $_GET['status']);
                }
                if (isset($_GET['date']) && $_GET['date']) {
                    $query = $query->whereDate('created_at', $_GET['date']);
                }
                $query = $query
                    ->where('user_id', $user_id)
                    ->where('number', '!=', null)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            $result = $query->get();
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()],
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
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
        $payment_method = $parameters['payment_method'];
        $reservation = Reservation::find($reservation_id);
        //$reservation->payment_method = $payment_method;
        $reservation->save();
        //$tokenApiKey = Config::get('sample_setting.token.token_api_key');
        $tokenApiKey = '1493ffc3-323d-470c-b617-c116e639f6b1';
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
        if ($payment_method == 0) {
            return view(
                'card.index',
                compact(
                    'reservation',
                    'amount',
                    'user',
                    'tokenApiKey',
                    'orderId',
                    'weekday'
                )
            );
        } elseif ($payment_method == 1) {
            return view(
                'cvs.index',
                compact(
                    'reservation',
                    'amount',
                    'user',
                    'tokenApiKey',
                    'orderId',
                    'weekday'
                )
            );
        } else {
            return redirect('https://blue-tourism-hokkaido.com/jp');
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
    public function cardCancel($order_id)
    {
        $logger = Log::channel('tgmdk')->getLogger();
        if ($logger instanceof LoggerInterface) {
            TGMDK_Logger::setLogger($logger);
        }
        $request_data = new CardCancelRequestDto();
        $request_data->setOrderId($order_id);

        //$request_data->setJpo(
        //    Helpers::generateJpo($request->request->get("jpo1"), $request->request->get("jpo2"))
        //);

        /*
         * 設定ファイルのパスを手動で指定する場合は以下のようにパスを指定してTGMDK_Configクラスのインスタンス生成をしておく
         * TGMDK_Config::getInstance("/home/test/laravel_sample/config/3GPSMDK.properties");
         */
        TGMDK_Config::getInstance(
            '/var/www/blue-tourism-hokkaido/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties'
        );
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        /*
         * マーチャントIDとマーチャント認証鍵を動的に設定する場合はexecuteメソッドの第2引数に以下のようにセットする
         */
        $props['merchant_ccid'] = 'A100000800000001100002cc';
        $props['merchant_secret_key'] =
            '5102fdcd8ddc7dd40673d04b2d91fb411f1efe69a573057382549b3cd5d076c9';
        $response_data = $transaction->execute($request_data, $props);
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
                    ->from('info@zenryo-ec.com')
                    ->subject("【全旅】予約確定メール");
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
            //return view('user.reservations.result');
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
        TGMDK_Config::getInstance(
            '/var/www/blue-tourism-hokkaido/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties'
        );
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        /*
         * マーチャントIDとマーチャント認証鍵を動的に設定する場合はexecuteメソッドの第2引数に以下のようにセットする
         */
        $props['merchant_ccid'] = 'A100000800000001100002cc';
        $props['merchant_secret_key'] =
            '5102fdcd8ddc7dd40673d04b2d91fb411f1efe69a573057382549b3cd5d076c9';
        $response_data = $transaction->execute($request_data, $props);
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
                    ->from('info@zenryo-ec.com')
                    ->subject("【全旅】予約確定メール");
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
            //return view('user.reservations.result');
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
            $dummy_reservation->status = 'dummy';
            $dummy_reservation->activity_date = 'dummy';
            $dummy_reservation->save();
        }
        // 新予約番号作成
        $count = Reservation::whereDate('created_at', Carbon::today())->count();
        $date = date('Ymd');
        $zeropadding = sprintf('%05d', $count);
        $new_order_id = 'ZR' . $date . $zeropadding;
        /*
        if ($new_order_id == $reservation->order_id && $reservation->order_id !=  $reservation->number) {
            $count = Reservation::whereDate('created_at', Carbon::today())->count();
            $date = date("Ymd");
            $count++;
            $zeropadding = sprintf('%05d', $count);
            $new_order_id = 'ZR' . $date . $zeropadding;
        }
*/
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
        TGMDK_Config::getInstance(
            '/var/www/blue-tourism-hokkaido/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties'
        );
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        /*
         * マーチャントIDとマーチャント認証鍵を動的に設定する場合はexecuteメソッドの第2引数に以下のようにセットする
         */
        $props['merchant_ccid'] = 'A100000800000001100002cc';
        $props['merchant_secret_key'] =
            '5102fdcd8ddc7dd40673d04b2d91fb411f1efe69a573057382549b3cd5d076c9';
        $response_data = $transaction->execute($request_data, $props);
        if ($response_data instanceof CardReAuthorizeResponseDto) {
            $mstatus = $response_data->getMstatus();
            $cancel_status = $this->cardCancel($reservation->order_id);
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
                ],
                function ($message) use ($reservation) {
                    if ($reservation->user->email) {
                        $message
                            ->to($reservation->user->email)
                            ->from('info@zenryo-ec.com')
                            ->subject('【全旅】決済金額変更メール');
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
}
