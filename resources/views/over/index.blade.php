@extends('layouts.parents')
@section('title', '全旅 - 有効期限切れ')
@section('content')
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">予約料金内訳</span>
                <span class="badge badge-secondary badge-pill">1</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <h6 class="my-0"><strong>プラン名：{{ $reservation->plan->name }}</strong></h6>
                    </div>
                </li>
@php
    $Number_of_reservations = json_decode($reservation->Number_of_reservations);
    $custom_view = true;
    if(array_key_exists('custom_flg', $Number_of_reservations)){
        if($Number_of_reservations->custom_flg == 1){
            $custom_view = false;
            for($i=0; $i<20; $i++){
                if(!is_null($Number_of_reservations->price_name->{$i+1})){
                    echo '<li class="list-group-item d-flex justify-content-between">';
                    echo '<div>';
                    echo '<h6 class="my-0">';
                    echo $Number_of_reservations->price_name->{$i+1} . " / &yen;" . $Number_of_reservations->typec_price->{$i+1} . " × " . $Number_of_reservations->typec_number->{$i+1};
                    echo '</h6>';
                    echo '</div>';
                    echo '<span class="text-muted">&yen;' . number_format($Number_of_reservations->typec_price->{$i+1} * $Number_of_reservations->typec_number->{$i+1}) . '</span>';
                    echo '</li>';
                }
            }
        }
    }
    if($custom_view){
        $arr = ['大人　','子供　','幼児　'];
        $tmp_arr=['a','b','c','d','e','f','g','h','i','j','k','l'];
        for($i=0; $i<count($arr); $i++){
            echo '<li class="list-group-item d-flex justify-content-between">';
            echo '<div>';
            echo '<h6 class="my-0">';
            for($j=0; $j<count($tmp_arr); $j++){
                if(array_key_exists(sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],1), $Number_of_reservations)){
                    if(array_key_exists(sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1), $Number_of_reservations)){
                        echo $arr[$i] . $priceName->name . " / &yen;" . $prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  . " × " . $Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1)};
                        echo '</h6>';
                        echo '</div>';
                        echo '<span class="text-muted">&yen;' . number_format($prices[0][$tmp_arr[$j]."_". ((int)$i + 1)] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $type_id,$tmp_arr[$j],$i+1)}) . '</span>';
                    }else{
                        echo $arr[$i] . $priceName->name . " / &yen;" . $prices[0][$tmp_arr[$j]."_". ((int)$i + 1)]  . " × " . 0;
                        echo '</h6>';
                        echo '</div>';
                        echo '<span class="text-muted">&yen;0</span>';
                    }
                }
            }
            echo '</li>';
        }
    }
@endphp
                <li class="list-group-item d-flex justify-content-between">
                    <strong>合計金額</strong>
                    <strong>&yen;{{ number_format($amount) }}</strong>
                </li>
            </ul>
        </div>

        <div class="col-md-8 order-md-1">
<!--
            <div class="alert alert-danger">
            </div>
-->
            <h5 class="mb-3 p-2 rounded bg-primary text-light">{{ $payment_method_str }}：有効期限切れ</h5>
            <hr class="mb-4">
            <div class="mb-3">
                    <label for="orderId">予約番号：</label><span>{{ $orderId }}</span>
            </div>
        </div>

    </div>
@endsection
