<?php

namespace App\Http\Controllers;

use App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;
use tgMdk\dto\CvsAuthorizeRequestDto;
use tgMdk\dto\CvsAuthorizeResponseDto;
use tgMdk\TGMDK_Config;
use tgMdk\TGMDK_Logger;
use tgMdk\TGMDK_Transaction;
use App\Models\Stock;
use App\Models\Reservation;
use Carbon\Carbon;
use Yasumi\Yasumi;
use App\Models\Price;
use App\Models\Plan;
use App\Models\PriceType;
use DateTime;

class CvsController extends Controller
{
    public function index()
    {
        return view('cvs/index')->with(
            [
                "amount" => "100",
                "orderId" => Helpers::generateOrderId()
            ]);
    }

    public function cvsAuthorize(Request $request)
    {

        $logger = Log::channel('tgmdk')->getLogger();
        if ($logger instanceof LoggerInterface) {
            TGMDK_Logger::setLogger($logger);
        }

        $payLimit = new DateTime();
        $payLimit->modify('+14 day');
        $request_data = new CvsAuthorizeRequestDto();
        $request_data->setServiceOptionType($request->request->get("serviceOptionType"));
        $request_data->setAmount($request->request->get("amount"));
        $request_data->setOrderId($request->request->get("orderId"));
        $request_data->setName1($request->request->get("name1"));
        $request_data->setName2($request->request->get("name2"));
        $request_data->setTelNo($request->request->get("telNo"));
        $request_data->setPayLimit($payLimit->format('Y/m/d'));
        $request_data->setPayLimitHhmm('23:59');
        //$request_data->setPushUrl($request->request->get("pushUrl"));
        $request_data->setPushUrl('https://nagaden-kanko.com/plan/push/mpi');
        $request_data->setPaymentType("0");

        TGMDK_Config::getInstance();
        $transaction = new TGMDK_Transaction();
        /*
        $response_data = $transaction->execute($request_data);
        */ 
        /*$props["merchant_ccid"] = "";
        $props["merchant_secret_key"] = "";*/
        $response_data = $transaction->execute($request_data);

        if ($response_data instanceof CvsAuthorizeResponseDto) {
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
            $prices = Price::select()
            ->where('plan_id' , $reservation->plan_id)
            ->where('type' , $typeid)
            ->get();
            $priceName = PriceType::select()
                ->where('number' , $typeid)
                ->first();
            Mail::send(['text' => 'user.reservations.cvs_pre_email'], [
                "number" => $reservation->number,
                "plan" => $reservation->plan->name,
                "date" => date('Y年m月d日', strtotime($reservation->fixed_datetime)),
                "activity" => $reservation->activity_date,
                "name_last" => $reservation->user->name_last,
                "name_first" => $reservation->user->name_first,
                "email" => $reservation->user->email,
                "tel" => $reservation->user->tel,
                "tel2" => $request->request->get("telNo"),
                "reservation" => $reservation,
                "payment" => 'コンビニ決済',
                "haraikomiUrl" => $response_data->getHaraikomiUrl(),
                "receiptNo" => $response_data->getReceiptNo(),
                "weekday" => $weekday,
                "amount" => $request->amount,
                'prices'        => $prices,
                'priceName'     => $priceName,
                'type_id'   => $typeid,
                'payLimit' => $payLimit
            ], function($message) use($reservation) {
                if ($reservation->user->email) {
                    $message
                    ->to($reservation->user->email)
                    ->bcc(['goontrip@nagaden-kanko.com'])
                    ->from('goontrip@nagaden-kanko.com')
                    ->subject("【コンビニ受付番号のお知らせ】長野電鉄株式会社");
	        }
            });
            // ベリトランスオーダーIDをDBへ格納
            $reservation->order_id = $request->request->get("orderId");
            // 決済ステータスをDBへ格納
            $reservation->status = '未決済';
            $reservation->save();
            // 在庫を減数
            $stock = Stock::select()
            ->where('plan_id', $reservation->plan->id)
            ->where('res_date', date('Y-m-d', strtotime($reservation->fixed_datetime)))
            ->where('price_type_id', $typeid)
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
                    //$stock->limit_number = $stock->limit_number - $count_member;
                    //$stock->save();
                } else {
                    //$stock->limit_number = $stock->limit_number - 1;
                    //$stock->save();
                }
            }
            //return view('user.reservations.result');
            return redirect('/cvs/result/' . $request->request->get("orderId"));
        }

        return view('cvs/index')->with(
            [
                'amount' => $request->request->get("amount")
            ]);

    }

    public function authorizeResult($orderId)
    {
        $response_data = session($orderId);
        if ($response_data instanceof CvsAuthorizeResponseDto) {
            return view('cvs/result')->with([
                'mstatus' => $response_data->getMstatus(),
                'vResultCode' => $response_data->getVResultCode(),
                'mErrMsg' => $response_data->getMerrMsg(),
                'orderId' => $response_data->getOrderId(),
                'receiptNo' => $response_data->getReceiptNo(),
                'haraikomiUrl' => $response_data->getHaraikomiUrl(),
            ]);
        } else {
            return view('cvs/result')->with([
                'mstatus' => null, 'vResultCode' => null, 'mErrMsg' => null, 'orderId' => null, 'receiptNo' => null,
                'haraikomiUrl' => null, 'message' => "error!"
            ]);
        }
    }

}
