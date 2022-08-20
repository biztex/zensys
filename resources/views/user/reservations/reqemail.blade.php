{{ $name_last }} {{ $name_first }}　様

この度は株式会社全旅からのリクエスト予約、誠にありがとうございました。

以下の内容にてリクエスト予約を承っておりますのでご確認ください。

※仮予約状態となっておりますので、必ず実施会社からの連絡をご確認ください

【リクエスト予約情報】
=====================================
予約番号　：　{{ $number }}
予約状況　：　リクエスト予約
プラン名　：　{{ $plan }}　　
お名前　　：　{{ $name_last }} {{ $name_first }}　様({{$kana_last}} {{$kana_first}})
メールアドレス ：　{{ $email }}
住所 ：　{{ $postalcode }}　{{ $prefecture }}{{ $address }}
電話番号　　：　{{ $tel }}
生年月日　　：　{{ $birth_year }}/{{ $birth_month }}/{{ $birth_day }}
当日緊急連絡先　　：　{{ $tel2 }}
予約日　　：　{{ $date }}
予約時間　：　{{ $activity }}
予約人数

----------------------------------------------------
@php
  $Number_of_reservations = json_decode($reservation->Number_of_reservations);
  $arr = ['大人　','子供　','幼児　'] ;
  $tmp_arr=['a','b','c','d','e','f','g','h','i','j','k','l'];
  for($i=0; $i<count($arr); $i++){
    sprintf('type%d_%s_%d_number', 10,$tmp_arr[$reservation->price_type],$i+1);
  }
 

@endphp