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
  echo $reservation->price_type;
  echo $type_id;
@endphp

