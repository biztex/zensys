@extends('layouts.parents')
@section('title', '全旅 - コンビニ決済')
@section('content')
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">予約料金</span>
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
                if(\App\Helpers::isInNumberOfReservations((array)$Number_of_reservations, $type_id, $tmp_arr[$j])) {
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
        </div>

        <div class="col-md-8 order-md-1">
<!--
            <div class="alert alert-danger">
            </div>
-->
            <h5 class="mb-3 p-2 rounded bg-primary text-light">コンビニ決済：決済請求</h5>
            <div class="row">
<!--
                <div class="col-1 col-sm-2"><img src="{{asset("img/CVS_SevenEleven.jpg")}}" alt="セブンイレブン"></div>
-->
                <div class="col-2 col-sm-2"><img src="{{asset("img/CVS_Famima.jpg")}}" alt="ファミリーマート"></div>
                <div class="col-1 col-sm-2"><img src="{{asset("img/CVS_Lawson.jpg")}}" alt="ローソン"></div>
                <div class="col-1 col-sm-2"><img src="{{asset("img/CVS_Ministop.jpg")}}" alt="ミニストップ"></div>
                <div class="col-2 col-sm-2"><img src="{{asset("img/CVS_Seicomart.jpg")}}" alt="セイコーマート"></div>
<!--
                <div class="col-1 col-sm-2"><img src="{{asset("img/CVS_Dailyyamazaki.jpg")}}" alt="デイリーヤマザキ"></div>
-->
            </div>
            <hr class="mb-4">
            <form method="post" action="{{ url('/cvs') }}" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="orderId">予約番号</label>
                    <div class="row">
                        <div class="col-12 col-sm-8">
                            <input type="text" class="form-control" id="reservation_id" name="resevation_id" value="{{ $reservation->number }}" readonly>
                            <input type="hidden" class="form-control" id="orderId" name="orderId" value="{{ $orderId }}" maxlength="100">
                        </div>
                    </div>

                </div>
                <div class="mb-3">
                    <label for="serviceOptionType">決済コンビニエンスストア</label>
                    <select class="form-select" id="serviceOptionType" name="serviceOptionType">
<!--
                        <option value="sej">セブンイレブン</option>
-->
                        <option value="econ">ファミリーマート、ローソン、ミニストップ、セイコーマート</option>
<!--
                        <option value="other">その他(デイリーヤマザキ)</option>
-->
                    </select>
                </div>
<!--
                <div class="mb-3">
                    <label for="amount">金額</label>
                    <input type="number" class="form-control" id="amount" name="amount" value=""
                           maxlength="8" required>
                </div>
-->
                <div class="mb-3">
                    <label for="name1">姓</label>
                    <input type="text" class="form-control" id="name1" name="name1" maxlength="20" value="{{ $user->name_last }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="name2">名</label>
                    <input type="text" class="form-control" id="name2" name="name2" maxlength="20" value="{{ $user->name_first }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="telNo">電話番号</label>
                    <input type="tel" class="form-control" id="telNo" name="telNo" maxlength="13" value="{{ $user->tel }}" readonly>
                    <p class="text-warning">※"-"(ハイフン)区切りも可能</p>
                </div>
                <input type="hidden" class="form-control" id="payLimit" name="payLimit" maxlength="10" value="{{ date('Y/m/d', strtotime("+1 week")) }}">
<!--
                <div class="mb-3">
                    <label for="payLimit">支払期限</label>
                    <input type="text" class="form-control" id="payLimit" name="payLimit" maxlength="10" required>
                    <p class="text-warning">※形式：YYYYMMDD or YYYY/MM/DD</p>
                </div>

                <div class="mb-3">
                    <label for="payLimitHhmm">支払期限時分</label>
                    <input type="text" class="form-control" id="payLimitHhmm" name="payLimitHhmm" maxlength="5">
                    <p class="text-warning">※形式：HH:mm or HHmm</p>

                </div>
                <div class="mb-3">
                    <label for="push_url">プッシュURL</label>
                    <input type="url" inputmode="url" class="form-control" id="push_url" name="pushUrl"  placeholder="" maxlength="256">
                </div>
-->
                <hr class="mb-4">
                <input type="hidden" class="form-control" id="amount" name="amount" value="{{ $amount }}">
                <input type="hidden" class="form-control" id="withCapture" name="withCapture" value="true">
                <input type="hidden" class="form-control" id="reservation_id" name="reservation_id" value="{{ $reservation->id }}">
                <input type="hidden" class="form-control" id="paymentType" name="paymentType" value="0">
                <button class="btn btn-success btn" id="proceed_payment" type="submit">受付番号取得</button>
            </form>
        </div>

    </div>
@endsection
