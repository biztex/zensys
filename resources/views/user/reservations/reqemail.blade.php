{{ $name_last }} {{ $name_first }}　様

※ご予約はまだ確定しておりません。

この度は当社ホームページからのお申込、誠にありがとうございました。

手配が完了しましたら、改めて決済依頼のご連絡をいたします。

【リクエスト予約情報】
=====================================
予約番号　：　{{ $number }}
予約状況　：　決済待ち
プラン名　：　{{ $plan }}　　
お名前　　：　{{ $name_last }} {{ $name_first }}　様({{$kana_last}} {{$kana_first}})
メールアドレス ：　{{ $email }}
住所 ：　{{ $postalcode }}　{{ $prefecture }}{{ $address }}
電話番号　　：　{{ $tel }}
生年月日　　：　{{ $birth_year }}/{{ $birth_month }}/{{ $birth_day }}
当日緊急連絡先　　：　{{ $tel2 }}
予約日　　：　{{ $date }}
予約人数
----------------------------------------------------
@php
  $Number_of_reservations = json_decode($reservation->Number_of_reservations);
  $arr = ['大人　','子供　','幼児　'];
  $tmp_arr=['a','b','c','d','e','f','g','h','i','j','k','l'];
  for($i=0; $i<count($arr); $i++){
    if(array_key_exists(sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$reservation->pType],$i+1), $Number_of_reservations)){
      echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$reservation->pType]."_". ((int)$i + 1)]  . " 円 × " . $Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$reservation->pType],$i+1)} . "=" . number_format((int)$prices[0][$tmp_arr[$reservation->pType]."_". ((int)$i + 1)]  * (int)$Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$reservation->pType],$i+1)}) . " 円" . "\n";
    }else{
      echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$reservation->pType]."_". ((int)$i + 1)]  . " 円 × " . 0 . "=" . number_format((int)$prices[0][$tmp_arr[$reservation->pType]."_". ((int)$i + 1)]  * 0) . " 円" . "\n";
    }
  }
@endphp

合計：{{ number_format($amount) }}円　※本予約確定後に決済用メールが送られます
----------------------------------------------------
=====================================
【予約内容の確認やキャンセル規定について】
予約内容の確認やキャンセル規定につきましては、以下ページよりご確認下さい。
尚、キャンセルをご希望の場合は直接当社へご連絡ください。
https://zenryo.zenryo-ec.info/detail.php?plan_id={{ $reservation->plan->id }}
=====================================
長野電鉄株式会社

〒380-0823 長野県長野市南千歳長電長野パーキング1F
TEL.026-227-3535
URL. https://nagaden-kanko.com/
=====================================