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
出発日　　：　{{ $date }}
予約日　　：　{{ date_format($reservation->created_at, 'Y年m月d日') }}
予約人数
----------------------------------------------------
@php
  $Number_of_reservations = json_decode($reservation->Number_of_reservations);
  $arr = ['大人　','子供　','幼児　'];
  $tmp_arr=['a','b','c','d','e','f','g','h','i','j','k','l'];
  for($i=0; $i<count($arr); $i++){
    if(array_key_exists(sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$reservation->price_type],$i+1), $Number_of_reservations)){
      echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$reservation->price_type]."_". ((int)$i + 1)]  . " 円 × " . $Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$reservation->price_type],$i+1)} . "=" . number_format((int)$prices[0][$tmp_arr[$reservation->price_type]."_". ((int)$i + 1)]  * (int)$Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$reservation->price_type],$i+1)}) . " 円" . "\n";
    }else{
      echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$reservation->price_type]."_". ((int)$i + 1)]  . " 円 × " . 0 . "=" . number_format((int)$prices[0][$tmp_arr[$reservation->price_type]."_". ((int)$i + 1)]  * 0) . " 円" . "\n";
    }
  }
@endphp

