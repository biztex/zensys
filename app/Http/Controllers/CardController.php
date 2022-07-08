<?php

namespace App\Http\Controllers;

use App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;
use tgMdk\dto\CardAuthorizeRequestDto;
use tgMdk\dto\CardAuthorizeResponseDto;
use tgMdk\dto\CardCancelRequestDto;
use tgMdk\dto\CardCancelResponseDto;
use tgMdk\TGMDK_Config;
use tgMdk\TGMDK_Logger;
use tgMdk\TGMDK_Transaction;
use App\Models\Stock;
use App\Models\Reservation;
use Carbon\Carbon;
use Yasumi\Yasumi;

class CardController extends Controller
{
    public function index()
    {
        return view('card/index')->with(
            [
                'tokenApiKey' => Config::get('sample_setting.token.token_api_key'),
                "amount" => "100",
                "orderId" => Helpers::generateOrderId()
            ]);
    }

    public function cardAuthorize(Request $request)
    {
        $logger = Log::channel('tgmdk')->getLogger();
        if ($logger instanceof LoggerInterface) {
            TGMDK_Logger::setLogger($logger);
        }
        $request_data = new CardAuthorizeRequestDto();
        $request_data->setAmount($request->request->get("amount"));
        $request_data->setOrderId($request->request->get("orderId"));
        $request_data->setToken($request->request->get("token"));
        $request_data->setWithCapture($request->request->get("withCapture"));

        $request_data->setJpo(
            Helpers::generateJpo($request->request->get("jpo1"), $request->request->get("jpo2"))
        );

        /*
         * 設定ファイルのパスを手動で指定する場合は以下のようにパスを指定してTGMDK_Configクラスのインスタンス生成をしておく
         * TGMDK_Config::getInstance("/home/test/laravel_sample/config/3GPSMDK.properties");
         */
        TGMDK_Config::getInstance("/var/www/blue-tourism-hokkaido/local_packages/veritrans-tgmdk/src/tgMdk/3GPSMDK.properties");
        $transaction = new TGMDK_Transaction();
        //$response_data = $transaction->execute($request_data);

        /*
         * マーチャントIDとマーチャント認証鍵を動的に設定する場合はexecuteメソッドの第2引数に以下のようにセットする
         */
         $props["merchant_ccid"] = "A100000800000001100002cc";
         $props["merchant_secret_key"] = "5102fdcd8ddc7dd40673d04b2d91fb411f1efe69a573057382549b3cd5d076c9";
         $response_data = $transaction->execute($request_data, $props);

        if ($response_data instanceof CardAuthorizeResponseDto) {
            $request->session()->put($request->request->get("orderId"), $response_data);
            // 予約者へメール通知
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
                "payment" => 'クレジットカード決済',
                "haraikomiUrl" => null,
                "receiptNo" => null,
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
            // 支払方法と回数をDBへ格納
            $reservation->jpo1 = $request->jpo1;
            $reservation->jpo2 = $request->jpo2;
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

    }

    public function authorizeResult($orderId)
    {
        $response_data = session($orderId);
        if ($response_data instanceof CardAuthorizeResponseDto) {
            return view('card/result')->with([
                'mstatus' => $response_data->getMstatus(),
                'vResultCode' => $response_data->getVResultCode(),
                'mErrMsg' => $response_data->getMerrMsg(),
                'orderId' => $response_data->getOrderId(),
                'resAuthCode' => $response_data->getResAuthCode(),
                'reqCardNumber' => $response_data->getReqCardNumber()
            ]);
        } else {
            return view('card/result')->with([
                'mstatus' => null, 'vResultCode' => null, 'mErrMsg' => null, 'orderId' => null, 'resAuthCode' => null,
                'reqCardNumber' => null, 'message' => "error!"
            ]);
        }
    }
}
