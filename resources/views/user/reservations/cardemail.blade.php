{{ $name_last }} {{ $name_first }}　様

この度は当社ホームページからのお申込、誠にありがとうございました。

手配が完了いたしました。お申込内容をご確認の上、{{ date_format($payment_limit, 'Y年m月d日') }}までに以下決済URLからお手続きください。

決済手続きが完了しましたら、ご予約が確定いたします。

【決済用URL】
=====================================
● クレジットカード決済URL　　：{{ $url_card }}
=====================================

【お申込情報】
=====================================
予約番号　：　{{ $number }}
予約状況　：　決済待ち
プラン名　：　{{ $plan }}
お名前　　：　{{ $name_last }} {{ $name_first }}　様
出発日　　：　{{ $date }}
予約日　　：　{{ date_format($reservation->created_at, 'Y年m月d日') }}
電話番号　：　{{ $tel }}
メール　　：　{{ $email }}
予約人数
----------------------------------------------------

@php
$Number_of_reservations = json_decode($reservation->Number_of_reservations);
$custom_view = true;
  if(array_key_exists('custom_flg', $Number_of_reservations)){
    if($Number_of_reservations->custom_flg == 1){
      $custom_view = false;
      $amount = 0;
      for($i=0; $i<20; $i++){
        if(!is_null($Number_of_reservations->price_name->{$i+1})){
          echo $Number_of_reservations->price_name->{$i+1} . " / " . $Number_of_reservations->typec_price->{$i+1} . " 円 × " . $Number_of_reservations->typec_number->{$i+1} . "=" . number_format($Number_of_reservations->typec_price->{$i+1} * $Number_of_reservations->typec_number->{$i+1}) . " 円" . "\n";
          $amount += $Number_of_reservations->typec_price->{$i+1} * $Number_of_reservations->typec_number->{$i+1};
        }
      }
    }
  }
  if($custom_view){
    $arr = ['大人　','子供　','幼児　'];
    $tmp_arr=['a','b','c','d','e','f','g','h','i','j','k','l'];
    for($i=0; $i<count($arr); $i++){
      for($j=0; $j<count($tmp_arr); $j++){
        if(\App\Helpers::isInNumberOfReservations((array)$Number_of_reservations, $type_id, $tmp_arr[$j])) {
          if(array_key_exists(sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1), $Number_of_reservations)){
            echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  . " 円 × " . $Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1)} . "=" . number_format((int)$prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  * (int)$Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1)}) . " 円" . "\n";
          }else{
            echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  . " 円 × " . 0 . "=" . number_format((int)$prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  * 0) . " 円" . "\n";
          }
        }
      }
    }
  }
@endphp

決済金額合計：{{ number_format($amount) }}円
----------------------------------------------------
=====================================
【予約内容の確認やキャンセル規定について】
予約内容の確認やキャンセル規定につきましては、以下ページよりご確認下さい。
尚、キャンセルをご希望の場合は直接当社へご連絡ください。
{{ url("detail.php?plan_id={$reservation->plan->id}") }}
=====================================
長野電鉄株式会社

〒380-0823 長野県長野市南千歳長電長野パーキング1F
TEL.026-227-3535
URL. {{ config('mail.custom.website') }}
=====================================
