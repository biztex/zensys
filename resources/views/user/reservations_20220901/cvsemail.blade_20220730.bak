{{ $name_last }} {{ $name_first }}　様

この度は株式会社全旅からの予約、誠にありがとうございました。

以下予約情報をご確認の上、以下決済URLからお手続きください。

【決済用URL】
=====================================
● コンビニエンスストア決済URL：{{ $url_cvs }}
=====================================

【予約情報】
=====================================
予約番号　：　{{ $number }}
予約状況　：　決済待ち
プラン名　：　{{ $plan }}　　
お名前　　：　{{ $name_last }} {{ $name_first }}　様
予約日　　：　{{ $date }}
予約時間　：　{{ $activity }}
電話番号　：　{{ $tel }}
メール　　：　{{ $email }}
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
