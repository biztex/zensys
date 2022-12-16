<head>
<link rel="stylesheet" href="{{asset('css/menu.css')}}">
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
  <script>
    $('#submit-send').click(function() {
      $(this).parents('form').attr('action', $('#submit-send').attr('data-action'));
      $(this).parents('form').attr('method', 'put');
      $(this).parents('form').submit();
      return true;
    });
  </script>
</head>
<body style="padding: 20px;">
    <form action="{{config('app.url')}}client/reservations/sendpaymentmail/{{ $reservation->id }}" method="POST">
      @csrf
      @method('PUT')
      {{--成功もしくは失敗のメッセージ--}}
      @if (Session::has('message'))
      <div class="alert alert-info">
          {{ session('message') }}
      </div>
      @endif
      <div style="text-align: center;">
        <input type="submit" id="submit-send" class="btn btn-danger" data-action="{{config('app.url')}}client/reservations/sendpaymentmail/{{ $reservation->id }}" value="この内容で送信" method="POST">
        <input type="hidden" id="custom_flg" name="status" value="{{ $reservation->status }}">
      </div>
    </form>
=====================================</br>
{{ $name_last }} {{ $name_first }}　様</br>
</br>
この度は当社ホームページからのお申込、誠にありがとうございました。</br>
</br>
手配が完了いたしました。{{ date_format($payment_limit, 'Y年m月d日') }}までに以下のURLより、コンビニ決済のお手続きをお済ませの上、受付番号を控えてコンビニよりご入金をお願いいたします。</br>
</br>
決済手続きが完了しましたら、ご予約が確定いたします。
</br>
【コンビニ決済手続きURL】</br>
=====================================</br>
● コンビニエンスストア決済URL：{{ $url_cvs }}</br>
=====================================</br>
</br>
【お申込情報】</br>
=====================================</br>
予約番号　：　{{ $number }}</br>
予約状況　：　未決済</br>
プラン名　：　{{ $plan }}</br>
お名前　　：　{{ $name_last }} {{ $name_first }}　様</br>
出発日　　：　{{ $date }}</br>
予約日　　：　{{ date_format($reservation->created_at, 'Y年m月d日') }}</br>
電話番号　：　{{ $tel }}</br>
メール　　：　{{ $email }}</br>
予約人数</br>
----------------------------------------------------</br>
@php
$Number_of_reservations = json_decode($reservation->Number_of_reservations);
$custom_view = true;
  if(array_key_exists('custom_flg', $Number_of_reservations)){
    if($Number_of_reservations->custom_flg == 1){
      $custom_view = false;
      $amount = 0;
      for($i=0; $i<20; $i++){
        if(!is_null($Number_of_reservations->price_name->{$i+1})){
          echo $Number_of_reservations->price_name->{$i+1} . " / " . $Number_of_reservations->typec_price->{$i+1} . " 円 × " . $Number_of_reservations->typec_number->{$i+1} . "=" . number_format($Number_of_reservations->typec_price->{$i+1} * $Number_of_reservations->typec_number->{$i+1}) . " 円" . "\n</br>";
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
            echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  . " 円 × " . $Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1)} . "=" . number_format((int)$prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  * (int)$Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1)}) . " 円" . "\n</br>";
          }else{
            echo $arr[$i] . $priceName->name . " / " . $prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  . " 円 × " . 0 . "=" . number_format((int)$prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  * 0) . " 円" . "\n</br>";
          }
        }
      }
    }
  }
@endphp
<br/>
決済金額合計：{{ number_format($amount) }}円</br>
----------------------------------------------------</br>
=====================================</br>
【予約内容の確認やキャンセル規定について】</br>
予約内容の確認やキャンセル規定につきましては、以下ページよりご確認下さい。</br>
尚、キャンセルをご希望の場合は直接当社へご連絡ください。</br>
<a href="{{ url("detail.php?plan_id={$reservation->plan->id}") }}">{{ url("detail.php?plan_id={$reservation->plan->id}") }}</a><br/>
=====================================</br>
長野電鉄株式会社</br>
</br>
〒380-0823 長野県長野市南千歳長電長野パーキング1F</br>
TEL.026-227-3535</br>
URL. {{ config('mail.custom.website') }}<br/>
=====================================</br>
</body>
