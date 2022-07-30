{{ $name_last }} {{ $name_first }}　様

この度は株式会社全旅からのご予約、誠にありがとうございました。

以下の内容にて予約を承っておりますのでご確認ください。

※キャンセルをご希望の場合は直接実施会社へご連絡ください

【予約情報】
=====================================
予約番号　：　{{ $number }}
予約状況　：　予約確定
プラン名　：　{{ $plan }}　　
お名前　　：　{{ $name_last }} {{ $name_first }}　様({{$kana_last}} {{$kana_first}})
メールアドレス ：　{{ $email }}
住所 ：　{{ $postalcode }}　{{ $prefecture }}{{ $address }}
電話番号　　：　{{ $tel }}
生年月日　　：　{{ $birth_year }}/{{ $birth_month }}/{{ $birth_day }}
当日緊急連絡先　　：　{{ $tel2 }}
予約日　　：　{{ $date }}
予約時間　：　{{ $activity }}
決済方法　：　{{ $payment }} （現地にてご案内いたします）
予約人数
----------------------------------------------------
@php
  $amount = 0;
  $arr = ['大人　','子供　','幼児　'] ;
  $tmp_arr=["A","B","C","D","E","F","G","H","H","J","K","L"];
  for ($i=0; $i<count($arr); $i++){
    echo $arr[$i] . $priceName->name . " / " . $prices[0][strtolower($tmp_arr[$reservation->price_type]."_". ((int)$i + 1))]  . " 円 × " . $reservation->{'type' . $i . '_number'} . "=" . number_format((int)$prices[0][strtolower($tmp_arr[$reservation->price_type]."_". ((int)$i + 1))]  * (int)$reservation->{'type' . $i . '_number'}) . " 円" . "\n";
    $amount += (int)$prices[0][strtolower($tmp_arr[$reservation->price_type]."_". ((int)$i + 1))]  * (int)$reservation->{'type' . $i . '_number'} ; 
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
