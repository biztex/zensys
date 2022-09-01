{{ $name_last }} {{ $name_first }}　様

※ご予約はまだ確定しておりません。

この度は当社ホームページからのお申込、誠にありがとうございました。

手配が完了いたしました。{{ $reservation->plan->payment_plus_day }}日以内に以下のURLより、コンビニ決済のお手続きをお済ませの上、受付番号を控えてコンビニよりご入金をお願いいたします。

【コンビニ決済手続きURL】
=====================================
● コンビニエンスストア決済URL：{{ $url_cvs }}
=====================================

【お申込情報】
=====================================
予約番号　：　{{ $number }}
予約状況　：　決済待ち
プラン名　：　{{ $plan }}　　
お名前　　：　{{ $name_last }} {{ $name_first }}　様
予約日　　：　{{ $date }}
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
        if(array_key_exists(sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],1), $Number_of_reservations)){
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
https://zenryo.zenryo-ec.info/detail.php?plan_id={{ $reservation->plan->id }}
=====================================
株式会社全旅

所在地	〒104-0061　東京都中央区銀座8-13-1　銀座三井ビルディング2F
電話番号	03-6264-3132
公式サイト:　 https://www.zenryo.co.jp
=====================================
