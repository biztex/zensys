<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use tgMdk\TGMDK_Exception;
use tgMdk\TGMDK_MerchantUtility;
use tgMdk\TGMDK_Config;
use tgMdk\TGMDK_Logger;
use tgMdk\TGMDK_Transaction;
use App\Models\Reservation;
use App\Models\Price;
use App\Models\Plan;
use App\Models\User;
use App\Models\PriceType;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Yasumi\Yasumi;
use DateTime;

class PushController extends Controller
{

    public function mpi(Request $request)
    {
        TGMDK_Config::getInstance();
        $body = $request->getContent();
        Log::debug("body:" . $body);

        $hmac = $request->header('content-hmac');
        Log::debug("content-hmac:" . $hmac);

        try {
            if (TGMDK_MerchantUtility::checkMessage($body, $hmac)) {
                $array1 = explode('&', $body);
                $res = array();
                foreach($array1 as $arr){
                    $arr0 = explode('=', $arr);
                    $res[$arr0[0]] = $arr0[1];
                }
                // 入金通知件数を取得
                $max = $res['numberOfNotify'];
                //入金結果を予約情報に反映
                for($i=1;$i <= $max; $i++){
                    $reservation = Reservation::where('order_id', $res[sprintf('orderId%04d', $i-1)])->first();
                    if(!is_null($reservation)){
                        $reservation->status = '予約確定';
                        $reservation->save();
                        // メール送信タイミング変更
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
                        $typeid=0;
                        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];
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
                        Mail::send(['text' => 'user.reservations.email'], [
                            "number" => $reservation->number,
                            "plan" => $reservation->plan->name,
                            "date" => date('Y年m月d日', strtotime($reservation->fixed_datetime)),
                            "activity" => $reservation->activity_date,
                            "name_last" => $reservation->user->name_last,
                            "name_first" => $reservation->user->name_first,
                            "email" => $reservation->user->email,
                            "tel" => $reservation->user->tel,
                            "tel2" => $reservation->user->tel2,
                            "reservation" => $reservation,
                            "payment" => 'コンビニ決済',
                            "haraikomiUrl" => null,
                            "receiptNo" => null,
                            "weekday" => $weekday,
                            "amount" => $amount
                        ], function($message) use($reservation) {
                            if ($reservation->user->email) {
                                $message
                                ->to($reservation->user->email)
                                //->bcc(['blue@quality-t.com', 'test.zenryo@gmail.com'])
                                //->bcc(['test.zenryo@gmail.com'])
                                ->bcc(['kaname-n@magokorobin.com', 'test@toebisu.jp'])
                                //->from('no-reply@blue-tourism-hokkaido.website')
                                ->from('test@toebisu.jp')
                                ->subject("【全旅】予約確定メール");
                        }
                        });
                        // メール送信タイミング変更
                        Mail::send(['text' => 'user.reservations.cvs_complete_email'], [
                            "number" => $reservation->number,
                        ], function($message) use($reservation) {
                            $message
                            //->to(['blue@quality-t.com', 'test.zenryo@gmail.com'])
                            //->from('blue@quality-t.com')
                            ->to('test@toebisu.jp')
                            ->bcc('kaname-n@magokorobin.com')
                            ->from('test@toebisu.jp')
                            ->subject("【全旅】コンビニ決済完了メール");
                        });
                    }
                }
                Log::debug('入金通知データの検証に成功しました。');
                return response("OK", 200);

            } else {
                Log::debug('入金通知データの検証に失敗しました。');

            }

        } catch (TGMDK_Exception $e) {
            Log::error('入金通知データの検証中に例外が発生しました。');

        }

        return response("NG", 500);
    }

}
