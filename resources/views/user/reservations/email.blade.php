{{ $name_last }} {{ $name_first }}　様

この度は株式会社全旅からのご予約、誠にありがとうございました。

以下の内容にて予約を承っておりますのでご確認ください。

※キャンセルをご希望の場合は直接実施会社へご連絡ください

【予約情報】
=====================================
@if ($haraikomiUrl)
払込票URL ：　{{ $haraikomiUrl }} （※振込期限：予約日から1週間）
@endif
@if ($receiptNo)
コンビニ払い用受付番号 ：　{{ $receiptNo }} （※振込期限：予約日から1週間）
@endif

予約番号　：　{{ $number }}
予約状況　：　予約確定
プラン名　：　{{ $plan }}　　
お名前　　：　{{ $name_last }} {{ $name_first }}　様
予約日　　：　{{ $date }}
予約時間　：　{{ $activity }}
電話番号　：　{{ $tel }}
メール　　：　{{ $email }}
決済方法　：　{{ $payment }}
予約人数
----------------------------------------------------
@php
$Number_of_reservations = json_decode($reservation->Number_of_reservations);
$custom_view = true;
  if(array_key_exists('custom_flg', $Number_of_reservations)){
    if($Number_of_reservations->custom_flg == 1){
      $custom_view = false;
      $amount = 0;
      for($i=0; $i<6; $i++){
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

=====================================
株式会社全旅

所在地	〒104-0061　東京都中央区銀座8-13-1　銀座三井ビルディング2F
電話番号	03-6264-3132
公式サイト:　 https://www.zenryo.co.jp
=====================================
