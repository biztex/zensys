<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon2_2.ico')}}" />

    <!-- css -->
    <link rel="stylesheet" href="{{asset('libs/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('libs/slick/slick-theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/add.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/adminlte/dist/css/adminlte.css')}}">

    <!-- javascript -->
    <script src="{{asset('libs/jquery/jquery-3.4.1.min.js')}}"></script>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
</head>

<body>
    <header class="page-header">
        <div class="header-inner">
            <a href="/" class="logo"><img src="{{asset('assets/img/logo3.png')}}" alt="" /></a>
            <a href="javascript:void(0)" class="nav-open"><i></i><span></span></a>
            <div class="nav-wrapper">
                <ul class="nav">
                    <li><a href="/">トップ</a></li>
                    <li><a href="/category/news">新着情報</a></li>
                    <li><a href="/category/tour">ツアー紹介</a></li>
                    <li><a href="/company">会社概要</a></li>
                    <li><a href="/contact">お問い合わせ</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main class="page-wrapper">
        <section class="section section--reserve section--with-top-margin">
            <div class="inner">

                @php
                    $com_date = new DateTime(date('Y-m-d'));
                    $end =  new DateTime($date);
                    $time = date('H');
                    
                    $interval = $end->diff($com_date);
                    
                    if($plan['res_type'] == 0){
                        $compare = intval($interval->format('%R%a')) + intval($plan['res_end_day']);
                    }

                    else{
                        $compare = intval($interval->format('%R%a')) + intval($plan['req_before_day']);
                    }
                    if($compare == 0){
                        $time = date('H');
                        if($plan['res_type'] == 0){
                            $compare = intval($plan['res_end_time']) - $time ;
                        }
                        else{
                            $compare = intval($plan['req_before_time']) - $time ;
                        }
                    }
                @endphp

                @if ($compare < 0)
                <div class="section__content">
                    <ul class="stepUl">
                        <li class="is_active">予約内容入力</li>
                        <li>予約内容確認</li>
                        <li>@if( request('is_request') == 0) 予約完了 @else リクエスト受付完了  @endif</li>
                    </ul>
                    <div class="reserveBox">
                        <h3 class="reserveBoxHd">予約内容入力</h3>
                        <form action="{{ url('reservations/confirm') }}" method="post"  class="reserveCont" >
                            @csrf
                            @if (Session::has('message'))
                                <div class="text-red alert alert-primary">
                                    {{ session('message') }}
                                </div>
                                @endif
                                @if ($errors->any())
                                <div>
                                    <ul  class="text-red alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <li  class="text-white">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">予約プラン名</h4>
                                <dl class="reserveDl01">
                                    <dt><img src="{{config('app.url')}}uploads/{{ $plan->file_path1 }}" /></dt>
                                    <dd>
                                        <p class="nameP"><?= htmlspecialchars(
                                            $plan['name']
                                        ) ?></p>
                                        <p class="txtP"><span>実施会社：</span><?= $companies[0]['name'] ?></p>
                                        <p class="txtP"><span>出発日：</span>{{ date('Y年m月d日', strtotime($date)) }}</p>
                                    </dd>
                                </dl>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">予約数</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th rowspan="0">料金</th></tr>
                                    @php
                                    if($stock_price_types){
                                        $arr = ['大人　','子供　','幼児　'] ;
                                        foreach ($stock_price_types as $i => $stock_price_type) {
                                            if($stock_price_type['type'] == $priceType['number']){

                                                echo '<tr><td>';
                                                echo '<input type="hidden" name="price_table_type" value="'. $priceType['number'] . '">';

                                                for($i = 1; $i < 4; $i++){
                                                    if ($stock_price_type[strtolower($stock['rank']) . '_'. $i] > 0){
                                                        echo '<div class="numberP">';

                                                        echo $arr[$i-1] . $priceType['name'] . " / " . number_format($stock_price_type[strtolower($stock['rank']) . '_'. $i]) ." 円";
                                                        echo '<input type="hidden" name="price_table_name[]" value="'. $arr[$i-1] . $priceType['name'] . '">';
                                  
                                                        echo '<input type="hidden" name="price_table_price[]" value="'. number_format($stock_price_type[strtolower($stock['rank']) . '_'. $i]) . '">';
                                                        $str = "type" . $priceType['number'] ."_". strtolower($stock['rank']) ."_" . $i . "_number";
                                                        @endphp
                                                        <p>
                                                            <input type="text" name="<?php echo 'type' . $priceType['number'] .'_'. strtolower($stock['rank']) .'_' . $i . '_number'?>" class="number" min="0" max="'.$stock->limit_number.'" placeholder="半角数字" value="{{old($str)}}"> 人
                                                            <span class="errorMessage"></span>
                                                        </p>
                                                        @php
                                                        echo '</div>';
                                                    }
                                                }
                                                echo '</td></tr>';
                                            }

                                        }
                                        echo '<input type="hidden" name="price_type" value="'. $priceType['number'] .'">';

                                    }else{
                                        foreach ($plan->prices as $i => $price) {
                                            echo ' <tr><td><div class="numberP">';


                                            if($stock['price']){
                                                echo $price->price_types->name . " / " . number_format($stock['price']) . " 円";
                                            }else{
                                                if ($price->week_flag == 0) {
                                                echo $price->price_types->name . " / " . number_format($price->price) . " 円";
                                                }
                                                if ($price->week_flag == 1) {
                                                echo $price->price_types->name . " / " . number_format($price->{$weekday}) . " 円";
                                                }

                                            }

                                            if ($i == 0) {
                                                echo '<p><input type="text" name="type' . $price->type . '_number" class="number" min="0" max="999" value="1"> 人</p>';
                                            } else {
                                                echo '<p><input type="text" name="type' . $price->type . '_number" class="number" min="0" max="999" value="0"> 人</p>';
                                            }
                                            echo '</div></td></tr>';
                                        }

                                    }



                                    @endphp

                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">申込者情報</h4>
                                <table class="reserveTable">
                                    <tr class="nameTr">
                                        <th>申込者氏名(漢字)<span class="requiredRed">※</span></th>
                                        <td>
                                            <div class="halfP">
                                                <span>姓</span>
                                                <div>
                                                    <input class="midIpt" id="name_last" type="text" name="name_last" value="{{ old('name_last') }}">
                                                    <span class="errorMessage"></span>
                                                </div>
                                            </div>
                                            <div class="halfP">
                                                <span>名</span>
                                                <div>
                                                    <input class="midIpt" id="name_first" type="text" name="name_first" value="{{ old('name_first') }}">
                                                    <span class="errorMessage"></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="nameTr">
                                        <th>申込者氏名(カナ)<span class="requiredRed">※</span></th>
                                        <td>
                                            <div class="halfP">
                                                <span>セイ</span>
                                                <div>
                                                    <input class="midIpt" type="text" id="kana_last" name="kana_last" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*"  value="{{ old('kana_last') }}"  required>
                                                    <span class="errorMessage"></span>
                                                </div>
                                            </div>
                                            <div class="halfP">
                                                <span>メイ</span>
                                                <div>
                                                    <input class="midIpt" type="text" id="kana_first" name="kana_first" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="{{ old('kana_first') }}" required>
                                                    <span class="errorMessage"></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>性別<span class="requiredRed">※</span></th>
                                        <td>
                                            <div class="radioBox">
                                                <label>
                                                    <input type="radio" value="0" name="radio_sex" checked>
                                                    男性
                                                </label>
                                                <label>
                                                    <input type="radio" value="1" name="radio_sex">
                                                    女性
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="ageTr">
                                            <th>年齢<span class="requiredRed">※</span></th>
                                            <td>
                                                <div>
                                                    <div class="ageP"> 
                                                        <input class="midIpt" type="text" name="age" value="{{ old('age')}}" required placeholder="半角数字"> <span>才</span>
                                                    </div>
                                                    <span class="errorMessage"></span>

                                                </div>
                                            </td>
                                        </tr>
                                    <tr>
                                        <th>生年月日<span class="requiredRed">※</span></th>
                                        <td>
                                            <div class="dateP">
                                                <select name="birth_year" required><option value="">選択してください</option>@for ($i = (date('Y') - 150) ; $i <= (date('Y')) ; $i++) <option value="{{ $i }}" @if ($i == old('birth_year')) selected @endif>{{ $i }}</option> @endfor</select> 年　<select name="birth_month" required><option value="">選択してください</option>@for ($i = 1 ; $i <= 12 ; $i++) <option value="{{ $i }}" @if ($i == old('birth_month')) selected @endif >{{ $i }}</option> @endfor</select> 月　<select name="birth_day" required><option value="">選択してください</option> @for ($i = 1 ; $i <= 31 ; $i++)<option value="{{ $i }}"  @if ($i == old('birth_day')) selected @endif >{{ $i }}</option> @endfor</select> 日
                                            </div>
                                            <span class="errorMessage"></span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス<span class="requiredRed">※</span></th>
                                        <td>
                                            <input type="email" name="email" id="email"  class="width-half" value="{{ old('email') }}" required>
                                            <span class="errorMessage"></span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス確認<span class="requiredRed">※</span></th>
                                        <td>
                                            <input class="width-half form-control" type="email" name="email_confirmation"  id="email-confirm" value="{{ old('email_confirmation') }}"  required>
                                            <span class="errorMessage"></span>
                                        </td>
                                    </tr>
                                    <tr class="zipTr h-adr">
                                        <th class="topTh">住所<span class="requiredRed">※</span></th>
                                        <td>
                                            <span class="p-country-name" style="display:none;">Japan</span>
                                            <div class="">
                                                <div class="zipP01">
                                                    <span>〒</span>
                                                    <input class="midIpt p-postal-code" type="text" name="postalcode" pattern="\d{7}" value="{{ old('postalcode') }}" placeholder="ハイフンなしで入力" required>
                                                </div>
                                                <span class="errorMessage"></span>
                                            </div>
                                            <select  id="contact_address_pref" name="prefecture" required class="p-region midIpt">
                                            <option value="北海道" @if(old('prefecture')=='北海道') selected @endif>北海道</option>
                                                <option value="青森県" @if(old('prefecture')=='青森県') selected @endif>青森県</option>
                                                <option value="岩手県" @if(old('prefecture')=='岩手県') selected @endif>岩手県</option>
                                                <option value="宮城県" @if(old('prefecture')=='宮城県') selected @endif>宮城県</option>
                                                <option value="秋田県" @if(old('prefecture')=='秋田県') selected @endif>秋田県</option>
                                                <option value="山形県" @if(old('prefecture')=='山形県') selected @endif>山形県</option>
                                                <option value="福島県" @if(old('prefecture')=='福島県') selected @endif>福島県</option>
                                                <option value="茨城県" @if(old('prefecture')=='茨城県') selected @endif>茨城県</option>
                                                <option value="栃木県" @if(old('prefecture')=='栃木県') selected @endif>栃木県</option>
                                                <option value="群馬県" @if(old('prefecture')=='群馬県') selected @endif>群馬県</option>
                                                <option value="埼玉県" @if(old('prefecture')=='埼玉県') selected @endif>埼玉県</option>
                                                <option value="千葉県" @if(old('prefecture')=='千葉県') selected @endif>千葉県</option>
                                                <option value="東京都" @if(old('prefecture')=='東京都') selected @endif>東京都</option>
                                                <option value="神奈川県" @if(old('prefecture')=='神奈川県') selected @endif>神奈川県</option>
                                                <option value="新潟県" @if(old('prefecture')=='新潟県') selected @endif>新潟県</option>
                                                <option value="富山県" @if(old('prefecture')=='富山県') selected @endif>富山県</option>
                                                <option value="石川県" @if(old('prefecture')=='石川県') selected @endif>石川県</option>
                                                <option value="福井県" @if(old('prefecture')=='福井県') selected @endif>福井県</option>
                                                <option value="山梨県" @if(old('prefecture')=='山梨県') selected @endif>山梨県</option>
                                                <option value="長野県" @if(old('prefecture')=='長野県') selected @endif>長野県</option>
                                                <option value="岐阜県" @if(old('prefecture')=='岐阜県') selected @endif>岐阜県</option>
                                                <option value="静岡県" @if(old('prefecture')=='静岡県') selected @endif>静岡県</option>
                                                <option value="愛知県" @if(old('prefecture')=='愛知県') selected @endif>愛知県</option>
                                                <option value="三重県" @if(old('prefecture')=='三重県') selected @endif>三重県</option>
                                                <option value="滋賀県" @if(old('prefecture')=='滋賀県') selected @endif>滋賀県</option>
                                                <option value="京都府" @if(old('prefecture')=='京都府') selected @endif>京都府</option>
                                                <option value="大阪府" @if(old('prefecture')=='大阪府') selected @endif>大阪府</option>
                                                <option value="兵庫県" @if(old('prefecture')=='兵庫県') selected @endif>兵庫県</option>
                                                <option value="奈良県" @if(old('prefecture')=='奈良県') selected @endif>奈良県</option>
                                                <option value="和歌山県" @if(old('prefecture')=='和歌山県') selected @endif>和歌山県</option>
                                                <option value="鳥取県" @if(old('prefecture')=='鳥取県') selected @endif>鳥取県</option>
                                                <option value="島根県" @if(old('prefecture')=='島根県') selected @endif>島根県</option>
                                                <option value="岡山県" @if(old('prefecture')=='岡山県') selected @endif>岡山県</option>
                                                <option value="広島県" @if(old('prefecture')=='広島県') selected @endif>広島県</option>
                                                <option value="山口県" @if(old('prefecture')=='山口県') selected @endif>山口県</option>
                                                <option value="徳島県" @if(old('prefecture')=='徳島県') selected @endif>徳島県</option>
                                                <option value="香川県" @if(old('prefecture')=='香川県') selected @endif>香川県</option>
                                                <option value="愛媛県" @if(old('prefecture')=='愛媛県') selected @endif>愛媛県</option>
                                                <option value="高知県" @if(old('prefecture')=='高知県') selected @endif>高知県</option>
                                                <option value="福岡県" @if(old('prefecture')=='福岡県') selected @endif>福岡県</option>
                                                <option value="佐賀県" @if(old('prefecture')=='佐賀県') selected @endif>佐賀県</option>
                                                <option value="長崎県" @if(old('prefecture')=='長崎県') selected @endif>長崎県</option>
                                                <option value="熊本県" @if(old('prefecture')=='熊本県') selected @endif>熊本県</option>
                                                <option value="大分県" @if(old('prefecture')=='大分県') selected @endif>大分県</option>
                                                <option value="宮崎県" @if(old('prefecture')=='宮崎県') selected @endif>宮崎県</option>
                                                <option value="鹿児島県" @if(old('prefecture')=='鹿児島県') selected @endif>鹿児島県</option>
                                                <option value="沖縄県" @if(old('prefecture')=='沖縄県') selected @endif>沖縄県</option>
                                            </select>
                                            <div class="zipP02">
                                                <p>市区郡町村</p>
                                                <input type="text" name="address" class="width-half p-locality p-street-address" value="{{ old('address') }}" required>
                                            </div>
                                            <div class="zipP02">
                                                <p>番地、アパート、マンション名等</p>
                                                <input class="width-half p-extended-address" type="text" name="extended" value="{{ old('extended') }}" required>

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>電話番号<span class="requiredRed">※</span></th>
                                        <td>
                                            <input class="" type="text" name="tel" pattern="\d{2,4}-?\d{2,4}-\d{3,4}" value="{{ old('tel') }}" required placeholder="ハイフンありで入力">
                                            <span class="errorMessage"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>緊急連絡先<span class="requiredRed">※</span></th>
                                        <td>
                                            <input class="" type="text" name="tel2" pattern="\d{2,4}-\d{2,4}-\d{3,4}" value="{{ old('tel2') }}" required placeholder="ハイフンありで入力">
                                            <span class="errorMessage"></span>
                                        </td>
                                    </tr>
                                    @if( isset($pieces) && count($pieces) > 0)
                                    <tr>
                                        <th>乗車地@if($plan->boarding_type == 1)<span class="requiredRed">※</span>@endif</th>
                                        <td>
                                            <select name="planBoarding"  @if($plan->boarding_type == 1) required @endif class="helperWidthMedium">
                                                <option value="">選択してください</option>
                                                @foreach($pieces as $piece)
                                                    <option value="{{$piece}}" @if(old('planBoarding') == $piece) selected @endif>{{$piece}}</option>
                                                @endforeach
                                            </select>
                                            <span class="errorMessage"></span>

                                        </td>
                                    </tr>
                                    @endif
                                    @if( isset($drops) && count($drops) > 0)
                                    <tr>
                                        <th>降車地@if($plan->drop_type == 1)<span class="requiredRed">※</span>@endif</th>
                                        <td>
                                            <select name="planDrop" class="helperWidthMedium" @if($plan->drop_type == 1) required @endif>
                                                <option value="">選択してください</option>
                                                @foreach($drops as $drop)
                                                    <option value="{{$drop}}" @if(old('planDrop') == $drop) seleted @endif>{{$drop}}</option>
                                                @endforeach
                                            </select>
                                            <span class="errorMessage"></span>

                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <script>
                                var value = 0;
                                $('input[type=radio][name=radio_sex]').click(function() {
                                   value=$(this).val
                                });

                                function copyJoint() {
                                    $("input[name='add_lastname[]']").first().val($("#name_last").val())
                                    $("input[name='add_firstname[]']").first().val($("#name_first").val())

                                    $("input[name='join_kana1[]']").first().val($("#kana_last").val())
                                    $("input[name='join_kana2[]']").first().val($("#kana_first").val())

                                    var selectedVal = $('input[name=radio_sex]:checked').val();
                                    $("select[name='join_sex[]']").first().val(selectedVal);
                                    $("input[name='join_age[]']").first().val($("input[name='age']").val());


                                    $("select[name='boarding[]']").first().val($("select[name='planBoarding']").val());
                                    $("select[name='drop[]").first().val($("select[name='planDrop']").val());

                                    $("select[name='birth_year_representative[]']").first().val($("select[name='birth_year']").val());
                                    $("select[name='birth_month_representative[]']").first().val($("select[name='birth_month']").val());
                                    $("select[name='birth_day_representative[]']").first().val($("select[name='birth_day']").val());
                                    $("input[name='postalcode_representative']").first().val($("input[name='postalcode']").val());
                                    $("select[name='prefecture_representative']").first().val($("select[name='prefecture']").val());
                                    $("input[name='address_representative']").first().val($("input[name='address']").val());
                                    $("input[name='extended_representative']").first().val($("input[name='extended']").val());
                                    $("input[name='tel_representative']").first().val($("input[name='tel']").val());

                                   // $(".reserveItem").append($("#reserveList"),html())
                                }

                                var count = 1; 

                               
                                
                                function addJoin(){
                                    count++;
                                    $("input[name='limit_number']").val(count);

                                    let comVal = parseInt($("input[name='limit_number']").val());

                                    @if ($plan->max_number != null)
                                        if(comVal >= {{$plan->max_number}}){
                                            $(".reserveAdd").css("opacity" , "0");
                                            $(".reserveAdd").css("visibility" , "hidden");
                                        }
                                    @endif
                                    
                                    $(".reserveAdd").before('<div class="reserveList helperNone'+ count +'" id="reserveList"></div>')
                                    $(".reserveList.helperNone"+count).load('{{config('app.url')}}html/reservation-companion.php' , function(e){

                                        $data_boarding = $(".par_boarding").children().clone();
                                        $data_drop = $(".par_drop").children().clone();
                                        $(this).find('.child_boarding').append($data_boarding);
                                        $(this).find('.child_drop').append($data_drop);
                                    });

                                  

                                    
                                    
                                }

                                $(document).on('click','.reserveDelete',function(){
                                    count--;
                                    $("input[name='limit_number']").val(count);
                                    let comVal = parseInt($("input[name='limit_number']").val());

                                    @if($plan->max_number != null)
                                        if(comVal < {{$plan->max_number}}){
                                            $(".reserveAdd").css("opacity" , "1");
                                            $(".reserveAdd").css("visibility" , "visible");
                                        }
                                    @endif
                                });

                              
                            </script>
                            <div class="reserveItem helperMarginTop">
                                <h4 class="reserveItemHd">旅行参加者情報</h4>
                                <p class="reflectsP"><a href="#reserveList" class="grayBtn helperTextOver" onclick="copyJoint()">申込者と参加者が同一の場合<br>こちらをクリック</a></p>
                                <a name="reserveList"></a>
                                <div class="reserveList" id="reserveList">
                                    <table class="reserveTable">
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(漢字)<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="halfP">
                                                    <span>姓</span>
                                                    <div>
                                                        <input class="midIpt" type="text" name="add_lastname[]" value="{!! old('add_lastname.0') !!}" required>
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="halfP">
                                                    <span>名</span>
                                                    <div>
                                                         <input class="midIpt" type="text" name="add_firstname[]" value="{{ old('add_firstname.0') }}" required>
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(カナ)<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="halfP">
                                                    <span>セイ</span>
                                                    <div>
                                                        <input class="midIpt" type="text" name="join_kana1[]" value="{{ old('join_kana1.0') }}" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" required>
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                                <div class="halfP">
                                                    <span>メイ</span>
                                                    <div>
                                                        <input class="midIpt" type="text" name="join_kana2[]" value="{{ old('join_kana2.0') }}"  pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" required>
                                                        <span class="errorMessage"></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="ageTr">
                                            <th>年齢<span class="requiredRed">※</span></th>
                                            <td>
                                                <div>
                                                    <div class="ageP">
                                                        <input class="midIpt" type="text" name="join_age[]"  value="{{ old('join_age.0') }}" required placeholder="半角数字">
                                                        <span>才</span>
                                                    </div>
                                                    <span class="errorMessage"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                        <th>生年月日<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="dateP">
                                                    <select name="birth_year_representative[]" required><option value="">選択してください</option>@for ($i = (date('Y') - 150) ; $i <= (date('Y')) ; $i++) <option value="{{ $i }}" @if ($i == old('birth_year_representative.0')) selected @endif>{{ $i }}</option> @endfor</select> 年　<select name="birth_month_representative[]" required><option value="">選択してください</option>@for ($i = 1 ; $i <= 12 ; $i++) <option value="{{ $i }}" @if ($i == old('birth_month_representative.0')) selected @endif >{{ $i }}</option> @endfor</select> 月　<select name="birth_day_representative[]" required><option value="">選択してください</option>@for ($i = 1 ; $i <= 31 ; $i++) <option value="{{ $i }}"  @if ($i == old('birth_day_representative.0')) selected @endif >{{ $i }}</option> @endfor</select> 日
                                                </div>
                                                <span class="errorMessage"></span>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>性別<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="radioBox">
                                                    <select name="join_sex[]">
                                                        <option value="0">
                                                        男性
                                                    <option value="1">
                                                        女性
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="h-adr">
                                            <th class="topTh">住所<span class="requiredRed">※</span></th>
                                            <td>
                                                <span class="p-country-name" style="display:none;">Japan</span>
                                                <div>
                                                    <div class="zipP01">
                                                        <span>〒</span>
                                                        <input class="midIpt p-postal-code" type="text" name="postalcode_representative" pattern="\d{3}-?\d{4}" value="{{ old('postalcode_representative') }}" placeholder="ハイフンなしで入力" required>
                                                    </div>
                                                    <span class="errorMessage"></span>
                                                </div>
                                              
                                                <select  id="contact_address_pref" name="prefecture_representative" required class="p-region midIpt">
                                                <option value="北海道" @if(old('prefecture')=='北海道') selected @endif>北海道</option>
                                                    <option value="青森県" @if(old('prefecture')=='青森県') selected @endif>青森県</option>
                                                    <option value="岩手県" @if(old('prefecture')=='岩手県') selected @endif>岩手県</option>
                                                    <option value="宮城県" @if(old('prefecture')=='宮城県') selected @endif>宮城県</option>
                                                    <option value="秋田県" @if(old('prefecture')=='秋田県') selected @endif>秋田県</option>
                                                    <option value="山形県" @if(old('prefecture')=='山形県') selected @endif>山形県</option>
                                                    <option value="福島県" @if(old('prefecture')=='福島県') selected @endif>福島県</option>
                                                    <option value="茨城県" @if(old('prefecture')=='茨城県') selected @endif>茨城県</option>
                                                    <option value="栃木県" @if(old('prefecture')=='栃木県') selected @endif>栃木県</option>
                                                    <option value="群馬県" @if(old('prefecture')=='群馬県') selected @endif>群馬県</option>
                                                    <option value="埼玉県" @if(old('prefecture')=='埼玉県') selected @endif>埼玉県</option>
                                                    <option value="千葉県" @if(old('prefecture')=='千葉県') selected @endif>千葉県</option>
                                                    <option value="東京都" @if(old('prefecture')=='東京都') selected @endif>東京都</option>
                                                    <option value="神奈川県" @if(old('prefecture')=='神奈川県') selected @endif>神奈川県</option>
                                                    <option value="新潟県" @if(old('prefecture')=='新潟県') selected @endif>新潟県</option>
                                                    <option value="富山県" @if(old('prefecture')=='富山県') selected @endif>富山県</option>
                                                    <option value="石川県" @if(old('prefecture')=='石川県') selected @endif>石川県</option>
                                                    <option value="福井県" @if(old('prefecture')=='福井県') selected @endif>福井県</option>
                                                    <option value="山梨県" @if(old('prefecture')=='山梨県') selected @endif>山梨県</option>
                                                    <option value="長野県" @if(old('prefecture')=='長野県') selected @endif>長野県</option>
                                                    <option value="岐阜県" @if(old('prefecture')=='岐阜県') selected @endif>岐阜県</option>
                                                    <option value="静岡県" @if(old('prefecture')=='静岡県') selected @endif>静岡県</option>
                                                    <option value="愛知県" @if(old('prefecture')=='愛知県') selected @endif>愛知県</option>
                                                    <option value="三重県" @if(old('prefecture')=='三重県') selected @endif>三重県</option>
                                                    <option value="滋賀県" @if(old('prefecture')=='滋賀県') selected @endif>滋賀県</option>
                                                    <option value="京都府" @if(old('prefecture')=='京都府') selected @endif>京都府</option>
                                                    <option value="大阪府" @if(old('prefecture')=='大阪府') selected @endif>大阪府</option>
                                                    <option value="兵庫県" @if(old('prefecture')=='兵庫県') selected @endif>兵庫県</option>
                                                    <option value="奈良県" @if(old('prefecture')=='奈良県') selected @endif>奈良県</option>
                                                    <option value="和歌山県" @if(old('prefecture')=='和歌山県') selected @endif>和歌山県</option>
                                                    <option value="鳥取県" @if(old('prefecture')=='鳥取県') selected @endif>鳥取県</option>
                                                    <option value="島根県" @if(old('prefecture')=='島根県') selected @endif>島根県</option>
                                                    <option value="岡山県" @if(old('prefecture')=='岡山県') selected @endif>岡山県</option>
                                                    <option value="広島県" @if(old('prefecture')=='広島県') selected @endif>広島県</option>
                                                    <option value="山口県" @if(old('prefecture')=='山口県') selected @endif>山口県</option>
                                                    <option value="徳島県" @if(old('prefecture')=='徳島県') selected @endif>徳島県</option>
                                                    <option value="香川県" @if(old('prefecture')=='香川県') selected @endif>香川県</option>
                                                    <option value="愛媛県" @if(old('prefecture')=='愛媛県') selected @endif>愛媛県</option>
                                                    <option value="高知県" @if(old('prefecture')=='高知県') selected @endif>高知県</option>
                                                    <option value="福岡県" @if(old('prefecture')=='福岡県') selected @endif>福岡県</option>
                                                    <option value="佐賀県" @if(old('prefecture')=='佐賀県') selected @endif>佐賀県</option>
                                                    <option value="長崎県" @if(old('prefecture')=='長崎県') selected @endif>長崎県</option>
                                                    <option value="熊本県" @if(old('prefecture')=='熊本県') selected @endif>熊本県</option>
                                                    <option value="大分県" @if(old('prefecture')=='大分県') selected @endif>大分県</option>
                                                    <option value="宮崎県" @if(old('prefecture')=='宮崎県') selected @endif>宮崎県</option>
                                                    <option value="鹿児島県" @if(old('prefecture')=='鹿児島県') selected @endif>鹿児島県</option>
                                                    <option value="沖縄県" @if(old('prefecture')=='沖縄県') selected @endif>沖縄県</option>
                                                </select>
                                                <div class="zipP02">
                                                    <p>市区郡町村</p>
                                                    <input type="text" name="address_representative" class="width-half p-locality p-street-address" value="{{ old('address_representative') }}" required>
                                                </div>
                                                <div class="zipP02">
                                                    <p>番地、アパート、マンション名等</p>
                                                    <input class="width-half p-extended-address" type="text" name="extended_representative"  value="{{ old('extended_representative') }}" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>電話番号<span class="requiredRed">※</span></th>
                                            <td>
                                                <input class="" type="text" name="tel_representative" pattern="\d{2,4}-?\d{2,4}-\d{3,4}" value="{{ old('tel_representative') }}" required placeholder="ハイフンありで入力">
                                            </td>
                                        </tr>
                                        @if( isset($pieces) && count($pieces) > 0)
                                        <tr class="par_boarding">
                                            <th>乗車地@if($plan->boarding_type == 1)<span class="requiredRed">※</span>@endif</th>
                                            <td>
                                                <select name="boarding[]" @if($plan->boarding_type == 1) required @endif class="helperWidthMedium">
                                                    <option value="">選択してください</option>
                                                    @foreach($pieces as $piece)
                                                        <option value="{{$piece}}" @if(old('boarding.0') == $piece) selected @endif>{{$piece}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="errorMessage"></span>
                                            </td>
                                        </tr>
                                        @endif
                                        @if( isset($pieces) && count($drops) > 0)
                                        <tr  class="par_drop">
                                            <th>降車地@if($plan->drop_type == 1)<span class="requiredRed">※</span>@endif</th>
                                            <td>
                                                <select name="drop[]" class="helperWidthMedium" @if($plan->drop_type == 1) required @endif>
                                                    <option value="">選択してください</option>
                                                    @foreach($drops as $drop)
                                                        <option value="{{$drop}}" @if(old('drop.0') == $drop) selected @endif>{{$drop}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="errorMessage"></span>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                    <p class="reserveDelete"></p>
                                </div>

                                <div class="reserveList helperNone0" id="reserveList"></div>
                                <input type="hidden" name="limit_number" value="1" />
                                <input type="hidden" name="companion_flag" value="{{$plan->companion_type}}" />

                                <p class="reserveAdd"><a href="#" class="grayBtn" onclick="addJoin()">同行者情報を追加</a></p>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">料金決済</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th>お支払方法<span class="requiredRed">※</span></th>
                                        <td>
                                            <select name="payment_method" class="midSelect" required >
                                                <option value="" selected>選択してください</option>
                                                @if ($plan->card == 1)
                                                        <option value="3" @if (old('payment_method') == 3) selected @endif>クレジットカード決済</option>
                                                @endif
                                                @if ($plan->spot == 1)
                                                        <option value="0" @if (old('payment_method') == 0) selected @endif>現地払い</option>
                                                @endif
                                                @if ($plan->prepay == 1)
                                                        <option value="1" @if (old('payment_method') == 1) selected @endif>銀行振込</option>
                                                @endif
                                                @if ($plan->cvs == 1)
                                                        <option value="2" @if (old('payment_method') == 2) selected @endif>コンビニ決済</option>
                                                @endif
                                               
                                             </select>
                                             <span class="errorMessage"></span>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">プラン内容</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th>実施会社</th>
                                        <td>{{ $companies[0]->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>プラン</th>
                                        <td>{{ $plan->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>目的地</th>
                                        <td>{{ $plan->destination }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            @if($plan->question_flag != 0)
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">予約者への質問</h4>
                                <div class="reserveTxt">
                                    <p>@if($plan->answer_flag == 1)<span class="requiredRed">※</span>@endif {{$plan->question_content}}</p>
                                    <textarea name="answer" class="reserveTextarea" @if($plan->answer_flag == 1) required @endif >{{ old('answer') }}</textarea>
                                    <span class="errorMessage"></span>

                                </div>
                            </div>
                            @endif
                            @if($plan->caution_content)
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">注意事項・その他</h4>
                                <div class="reserveTxt">
                                    <p>{{ $plan->caution_content }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">キャンセル規定</h4>
                                <div class="reserveTxt">
                                    <p>{!! $plan->cancel !!}</p>
                                </div>

                            </div>
                            <div class="reserveAgree">
                              <label for="agree01" class="checkBox01">
                                <input type="checkbox" name="agree" id="agree01" required>
                                <p><b>@if($plan->notice)
                                    <a href="{{$plan->notice}}" target="_blank">「旅行条件書」</a>
                                    @elseif($plan->file_path11)
                                    <a href="https://zenryo.zenryo-ec.info/uploads/{{$plan->file_path11}}" target="_blank">「旅行条件書」</a>
                                    @endif
                                    「注意事項」
                                    <a href="{{$companies[0]->url2}}" target="_blank">「個人情報の取扱に関する基本方針」</a>を確認しました</b></p>
                              </label>
                              <p>上記をチェックし、下記ボタンよりお申し込みください。</p>
                            </div>
                            <input type="hidden" name="plan_id" value="{{ request('plan_id')  }}">
                            <input type="hidden" name="date" value="{{ request('date')  }}">
                            <input type="hidden" name="is_request" value="{{ request('is_request')  }}">
                            <ul class="reserveButton">
                                <li><button class="btnLink01">確認画面へ進む</button></li>
                            </ul>
                        </form>
                    </div>
                </div>
                @else
                <div class="section__content col-md-6 offset-md-3 pt-4"  style="height: 100vh ; ">
                    <div class="reserveBox">
                        <div class="inner">
                            <div class="section__content  pt-4 pb-4 pl-2 pr-2 text-center">
                                <div class="warning">
                                    <div class="">
                                        <h2>この予約は終了しました。</h2>
                                        <a class="col-md-2 d-block offset-md-5 mt-4 px-4 py-2 bg-warning" href="https://zenryo.zenryo-ec.info/detail.php?plan_id={{ request('plan_id')}}">戻る</a>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                    
                </div>

               <script type='text/javascript'>window.top.location='https://zenryo.zenryo-ec.info/detail.php?plan_id={{ request('plan_id')}}';</script>
              
                @endif
            </div>
        </section>

    </main>

    <footer class="page-footer">
        <div class="wrapper">
            <div class="footer-top">
                <div class="container">
                    <p class="syamei_footer">長野電鉄株式会社</p>
                    <a href="/" class="logo syamei_footer_logo"><img src="https://zenryo.zenryo-ec.info/assets/img/logo3.png" alt="" /></a>
                    <div class="company-info">
                        <!-- <p class="company-name">長野電鉄株式会社</p> -->
                        <p class="post">〒380-0823</p>
                        <p class="address">長野県長野市南千歳1-17-7<br>長電長野パーキング1F<br class="sp"></p>
                        <div>
                            <p class="open"></p>
                            <p class="tel-mail"><a href="tel:026-227-3535 ">TEL：026-227-3535 </a> <a href="mailto:"></a></p>
                        </div>
                        <div class="social-link">
                            <div class="facebook">
                                <a href="https://www.facebook.com/NagadenTravel/" target="_blank">Facebook</a>
                            </div>
                           <!-- <div class="line">
                                <a href="#" target="_blank">Line</a>
                            </div>-->
                            <div class="instagram">
                                <a href="https://instagram.com/nagaden.travel?igshid=YmMyMTA2M2Y=" target="_blank">Instagram</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-middle">
                <div class="menu-list">
                    <span class="menu"><a href="/">トップ</a></span>
                    <span class="menu"><a href="/company">会社概要</a></span>
                    <span class="menu"><a href="/category/news">新着情報</a></span>
                    <span class="menu"><a href="/contact">お問い合わせ</a></span>
                    <span class="menu"><a href="/category/tour">ツアー紹介</a></span>
                    <span class="menu"><a href="/agreement">旅行業約款</a></span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="copyright">Copyright © 長野電鉄株式会社 All Rights Reserved.</p>
        </div>
    </footer>

    <script src="{{asset('libs/slick/slick.min.js')}}"></script>
    <script src="{{asset('assets/js/theme.js')}}"></script>
    <script>
         let comVal_cs = parseInt($("input[name='limit_number']").val());

        if({{$plan->companion_type}} == 2){
            $(".reserveAdd").css("opacity" , "0");
            $(".reserveAdd").css("visibility" , "hidden");
        }
       

        $(".reserveAdd a").click(function(e){
            e.preventDefault();
        })


        function validate(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
            // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\./;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }
      
        $(".btnLink01").click(function(e){
            e.preventDefault();
            let top_pos = null;
            var num_flag = false;
            //予約数
            $(".number").each(function(){
                let chars = $(this).val();
                if(chars !== ""){
                    if ($.isNumeric(chars)) {
                        $(this).removeClass("error");
                        $(this).next().removeClass("error");
                    }
                    else{
                        $(this).addClass("error");
                        $(this).next().text("※半角数字で入力してください。");
                        $(this).next().addClass("error");
                        if(top_pos == null){
                            top_pos = $(this).offset().top;
                        }
                    }

                    num_flag = true;
                }
                else{
                    $(this).removeClass("error");
                    $(this).next().removeClass("error");
                }
                
            });
            if(num_flag == false){
                $(".number").addClass("error");
                $(".number").next().text("※予約数を入力してください。");
                $(".number").next().addClass("error");
                if(top_pos == null){
                    top_pos = $(".number").first().offset().top;
                }
            }
            
            //姓
            if($("input[name='name_last']").val() === ''){
                $("input[name='name_last']").addClass("error");
                $("input[name='name_last']").next().addClass("error");
                $("input[name='name_last']").next().text("※姓を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='name_last']").offset().top;
                }
            }
            else{
                $("input[name='name_last']").removeClass("error");
                $("input[name='name_last']").next().removeClass("error");
            }

            //名
            if($("input[name='name_first']").val() === ''){
                $("input[name='name_first']").addClass("error");
                $("input[name='name_first']").next().addClass("error");
                $("input[name='name_first']").next().text("※名を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='name_first']").offset().top;
                }
            }
            else{
                $("input[name='name_first']").removeClass("error");
                $("input[name='name_first']").next().removeClass("error");
            }

            //	セイ

            let regexp = /[\u{3000}-\u{301C}\u{30A1}-\u{30F6}\u{30FB}-\u{30FE}]/mu;

            if($("input[name='kana_last']").val() === ''){
                $("input[name='kana_last']").addClass("error");
                $("input[name='kana_last']").next().addClass("error");
                $("input[name='kana_last']").next().text("※セイを入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='kana_last']").offset().top;
                }
            }
            else{
                if(regexp.test($("input[name='kana_last']").val()) == true){
                    $("input[name='kana_last']").removeClass("error");
                    $("input[name='kana_last']").next().removeClass("error");
                }
                else{
                    $("input[name='kana_last']").addClass("error");
                    $("input[name='kana_last']").next().addClass("error");
                    $("input[name='kana_last']").next().text("※カナで入力してください");
                    if(top_pos == null){
                        top_pos = $("input[name='kana_last']").offset().top;
                    }
                }
              
            }

            //メイ
            if($("input[name='kana_first']").val() === ''){
                $("input[name='kana_first']").addClass("error");
                $("input[name='kana_first']").next().addClass("error");
                $("input[name='kana_first']").next().text("※メイを入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='kana_first']").offset().top;
                }
            }
            else{
                if(regexp.test($("input[name='kana_first']").val()) == true){
                    $("input[name='kana_first']").removeClass("error");
                    $("input[name='kana_first']").next().removeClass("error");
                }
                else{
                    $("input[name='kana_first']").addClass("error");
                    $("input[name='kana_first']").next().addClass("error");
                    $("input[name='kana_first']").next().text("※カナで入力してください");
                    if(top_pos == null){
                        top_pos = $("input[name='kana_first']").offset().top;
                    }
                }
              
            }

            //年齢
            let ages = $("input[name='age']").val();

            if(ages !== ""){
                if ($.isNumeric(ages)) {
                    $("input[name='age']").removeClass("error");
                    $("input[name='age']").parent().next().removeClass("error");
                }
                else{
                    $("input[name='age']").addClass("error");
                    $("input[name='age']").parent().next().text("※半角数字で入力してください。");
                    $("input[name='age']").parent().next().addClass("error");
                    if(top_pos == null){
                        top_pos = $("input[name='age']").offset().top;
                    }
                }
            }
                
            else {
                $("input[name='age']").addClass("error");
                $("input[name='age']").parent().next().text("※年齢を入力してください。");
                $("input[name='age']").parent().next().addClass("error");
                if(top_pos == null){
                    top_pos = $("input[name='age']").offset().top;
                }
            }

            //生年月日
            let year = $("select[name='birth_year']").val();
            let month = $("select[name='birth_month']").val();
            let day = $("select[name='birth_day']").val();

            if(year !== "" && month !== "" && day !== "" ){
                $("select[name='birth_year']").removeClass("error");
                $("select[name='birth_month']").removeClass("error");
                $("select[name='birth_day']").removeClass("error");
                $("select[name='birth_year']").parent().next().removeClass("error");
            }
                
            else {
                $("select[name='birth_year']").addClass("error");
                $("select[name='birth_month']").addClass("error");
                $("select[name='birth_day']").addClass("error");
                $("select[name='birth_year']").parent().next().text("※生年月日を入力してください。");
                $("select[name='birth_year']").parent().next().addClass("error");
                if(top_pos == null){
                    top_pos = $("select[name='birth_year']").offset().top;
                }
            }



            let regexp_email = /^\b[A-Z0-9._%-+]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;


             //メールアドレス
             if($("input[name='email']").val() == ''){
                $("input[name='email']").addClass("error");
                $("input[name='email']").next().addClass("error");
                $("input[name='email']").next().text("※メールアドレスを入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='email']").offset().top;
                }
            }
            else{
                if(regexp_email.test($("input[name='email']").val()) == true){
                    $("input[name='email']").removeClass("error");
                    $("input[name='email']").next().removeClass("error");
                }
                else{
                    $("input[name='email']").addClass("error");
                    $("input[name='email']").next().addClass("error");
                    $("input[name='email']").next().text("※メールアドレス入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='email']").offset().top;
                    }
                }
              
            }

             //確認メールアドレス
             if($("input[name='email_confirmation']").val() == ''){
                $("input[name='email_confirmation']").addClass("error");
                $("input[name='email_confirmation']").next().addClass("error");
                $("input[name='email_confirmation']").next().text("※メールアドレスを入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='email_confirmation']").offset().top;
                }
            }
            else{
                if(regexp_email.test($("input[name='email_confirmation']").val()) == true){
                    if($("input[name='email_confirmation']").val() == $("input[name='email']").val()){
                        $("input[name='email_confirmation']").removeClass("error");
                        $("input[name='email_confirmation']").next().removeClass("error");
                    }
                    else{
                        $("input[name='email_confirmation']").addClass("error");
                        $("input[name='email_confirmation']").next().addClass("error");
                        $("input[name='email_confirmation']").next().text("※メールアドレスは一致ではありません。");
                        if(top_pos == null){
                            top_pos = $("input[name='email_confirmation']").offset().top;
                        }
                    }
     

                    
                }
                else{
                    $("input[name='email_confirmation']").addClass("error");
                    $("input[name='email_confirmation']").next().addClass("error");
                    $("input[name='email_confirmation']").next().text("※メールアドレス入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='email_confirmation']").offset().top;
                    }
                }
              
            }




            
            
            
            //郵便番号
            let regexpZip = /^\d{7}$/;
            
            if($("input[name='postalcode']").val() == ''){
                $("input[name='postalcode']").addClass("error");
                $("input[name='postalcode']").parent().next().addClass("error");
                $("input[name='postalcode']").parent().next().text("※郵便番号を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='postalcode']").offset().top;
                }
            }
            else{
                if(regexpZip.test($("input[name='postalcode']").val()) == true){
                    $("input[name='postalcode']").removeClass("error");
                    $("input[name='postalcode']").parent().next().removeClass("error");
                }
                else{
                    $("input[name='postalcode']").addClass("error");
                    $("input[name='postalcode']").parent().next().addClass("error");
                    $("input[name='postalcode']").parent().next().text("※郵便番号(半角数字)入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='postalcode']").offset().top;
                    }
                }
            
            }

            if($("input[name='postalcode_representative']").val() == ''){
                $("input[name='postalcode_representative']").addClass("error");
                $("input[name='postalcode_representative']").parent().next().addClass("error");
                $("input[name='postalcode_representative']").parent().next().text("※郵便番号を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='postalcode_representative']").offset().top;
                }
            }
            else{
                if(regexpZip.test($("input[name='postalcode_representative']").val()) == true){
                    $("input[name='postalcode_representative']").removeClass("error");
                    $("input[name='postalcode_representative']").parent().next().removeClass("error");
                }
                else{
                    $("input[name='postalcode_representative']").addClass("error");
                    $("input[name='postalcode_representative']").parent().next().addClass("error");
                    $("input[name='postalcode_representative']").parent().next().text("※郵便番号(半角数字)入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='postalcode_representative']").offset().top;
                    }
                }
            
            }

            //市区郡町村

            if($("input[name='address']").val() !== "" ){
                $("input[name='address']").removeClass("error");
            }
                
            else {
                $("input[name='address']").addClass("error");
                if(top_pos == null){
                    top_pos = $("input[name='address']").offset().top;
                }
            }
            if($("input[name='address_representative']").val() !== "" ){
                $("input[name='address_representative']").removeClass("error");
            }
                
            else {
                $("input[name='address_representative']").addClass("error");
                if(top_pos == null){
                    top_pos = $("input[name='address_representative']").offset().top;
                }
            }
            //番地、アパート、マンション名等<

            if($("input[name='extended']").val() !== "" ){
                $("input[name='extended']").removeClass("error");
            }
                
            else {
                $("input[name='extended']").addClass("error");
                if(top_pos == null){
                    top_pos = $("input[name='address']").offset().top;
                }
            }

            if($("input[name='extended_representative']").val() !== "" ){
                $("input[name='extended_representative']").removeClass("error");
            }
                
            else {
                $("input[name='extended_representative']").addClass("error");
                if(top_pos == null){
                    top_pos = $("input[name='extended_representative']").offset().top;
                }
            }


            //電話番号
            let regexpPhone = /^\d{2,4}-?\d{2,4}-\d{3,4}$/;
            
            if($("input[name='tel']").val() == ''){
                $("input[name='tel']").addClass("error");
                $("input[name='tel']").next().addClass("error");
                $("input[name='tel']").next().text("※電話番号を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='tel']").offset().top;
                }
            }
            else{
                if(regexpPhone.test($("input[name='tel']").val()) == true){
                    $("input[name='tel']").removeClass("error");
                    $("input[name='tel']").next().removeClass("error");
                }
                else{
                    $("input[name='tel']").addClass("error");
                    $("input[name='tel']").next().addClass("error");
                    $("input[name='tel']").next().text("※電話番号(半角数字)入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='tel']").offset().top;
                    }
                }
            
            }

            if($("input[name='tel_representative']").val() == ''){
                $("input[name='tel_representative']").addClass("error");
                $("input[name='tel_representative']").next().addClass("error");
                $("input[name='tel_representative']").next().text("※電話番号を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='tel_representative']").offset().top;
                }
            }
            else{
                if(regexpPhone.test($("input[name='tel_representative']").val()) == true){
                    $("input[name='tel_representative']").removeClass("error");
                    $("input[name='tel_representative']").next().removeClass("error");
                }
                else{
                    $("input[name='tel_representative']").addClass("error");
                    $("input[name='tel_representative']").next().addClass("error");
                    $("input[name='tel_representative']").next().text("※電話番号(半角数字)入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='tel_representative']").offset().top;
                    }
                }
            
            }


            //緊急連絡先
            if($("input[name='tel2']").val() == ''){
                $("input[name='tel2']").addClass("error");
                $("input[name='tel2']").next().addClass("error");
                $("input[name='tel2']").next().text("※緊急連絡先を入力してください");
                if(top_pos == null){
                    top_pos = $("input[name='tel']").offset().top;
                }
            }
            else{
                if(regexpPhone.test($("input[name='tel2']").val()) == true){
                    $("input[name='tel2']").removeClass("error");
                    $("input[name='tel2']").next().removeClass("error");
                }
                else{
                    $("input[name='tel2']").addClass("error");
                    $("input[name='tel2']").next().addClass("error");
                    $("input[name='tel2']").next().text("※緊急連絡先(半角数字)入力形式を確認してください");
                    if(top_pos == null){
                        top_pos = $("input[name='tel2']").offset().top;
                    }
                }
            
            }

            //乗車地

            if($("select[name='planBoarding']").attr("required") != null){
                if($("select[name='planBoarding']").val() == ''){
                    $("select[name='planBoarding']").addClass("error");
                    $("select[name='planBoarding']").next().addClass("error");
                    $("select[name='planBoarding']").next().text("※乗車地を入力してください。");
                    if(top_pos == null){
                        top_pos = $("select[name='planBoarding']").offset().top;
                    }
                }
                else{
                    $("select[name='planBoarding']").removeClass("error");
                    $("select[name='planBoarding']").next().removeClass("error");
                }
            };

            if($("select[name='boarding[]']").length > 0){
                $("select[name='boarding[]']").each(function(){
                    if($(this).attr("required") != null){
                        if($(this).val() == ''){
                            $(this).addClass("error");
                            $(this).next().addClass("error");
                            $(this).next().text("※乗車地を入力してください。");
                            if(top_pos == null){
                                top_pos = $(this).offset().top;
                            }
                        }
                        else{
                            $(this).removeClass("error");
                            $(this).next().removeClass("error");
                        }
                    };
                });
            }
           

            //降車地

            if($("select[name='planDrop']").attr("required") != null){
                if($("select[name='planDrop']").val() == ''){
                    $("select[name='planDrop']").addClass("error");
                    $("select[name='planDrop']").next().addClass("error");
                    $("select[name='planDrop']").next().text("※降車地を入力してください。");
                    if(top_pos == null){
                        top_pos = $("input[name='planDrop']").offset().top;
                    }
                }
                else{
                    $("select[name='planDrop']").removeClass("error");
                    $("select[name='planDrop']").next().removeClass("error");
                }
            };


            if($("select[name='drop[]']").length > 0){
                $("select[name='drop[]']").each(function(){
                    if($(this).attr("required") != null){
                        if($(this).val() == ''){
                            $(this).addClass("error");
                            $(this).next().addClass("error");
                            $(this).next().text("※降車地を入力してください。");
                            if(top_pos == null){
                                top_pos = $(this).offset().top;
                            }
                        }
                        else{
                            $(this).removeClass("error");
                            $(this).next().removeClass("error");
                        }
                    };
                });
            }



            //姓
            $("input[name='add_lastname[]']").each(function(){
                if($(this).val() === ''){
                    $(this).addClass("error");
                    $(this).next().addClass("error");
                    $(this).next().text("※姓を入力してください");
                    if(top_pos == null){
                        top_pos = $("input[name='name_last']").offset().top;
                    }
                }
                else{
                    $(this).removeClass("error");
                    $(this).next().removeClass("error");
                }   

            })

            //名
            $("input[name='add_firstname[]']").each(function(){
                if($(this).val() === ''){
                    $(this).addClass("error");
                    $(this).next().addClass("error");
                    $(this).next().text("※名を入力してください");
                    if(top_pos == null){
                        top_pos = $("input[name='name_last']").offset().top;
                    }
                }
                else{
                    $(this).removeClass("error");
                    $(this).next().removeClass("error");
                }   

            })


            $("input[name='join_kana1[]']").each(function(){
            
                if($(this).val() === ''){
                    $(this).addClass("error");
                    $(this).next().addClass("error");
                    $(this).next().text("※セイを入力してください");
                    if(top_pos == null){
                        top_pos = $(this).offset().top;
                    }
                }
                else{
                    if(regexp.test($(this).val()) == true){
                        $(this).removeClass("error");
                        $(this).next().removeClass("error");
                    }
                    else{
                        $(this).addClass("error");
                        $(this).next().addClass("error");
                        $(this).next().text("※カナで入力してください");
                        if(top_pos == null){
                            top_pos = $(this).offset().top;
                        }
                    }
                
                }

            });

            //メイ

            $("input[name='join_kana2[]']").each(function(){

                if($(this).val() === ''){
                    $(this).addClass("error");
                    $(this).next().addClass("error");
                    $(this).next().text("※メイを入力してください");
                    if(top_pos == null){
                        top_pos = $(this).offset().top;
                    }
                }
                else{
                    if(regexp.test($(this).val()) == true){
                        $(this).removeClass("error");
                        $(this).next().removeClass("error");
                    }
                    else{
                        $(this).addClass("error");
                        $(this).next().addClass("error");
                        $(this).next().text("※カナで入力してください");
                        if(top_pos == null){
                            top_pos = $(this).offset().top;
                        }
                    }
                
                }
            })


            //年齢

            $("input[name='join_age[]']").each(function(){
                let ages = $(this).val();
                if(ages !== ""){
                    if ($.isNumeric(ages)) {
                        $(this).removeClass("error");
                        $(this).parent().next().removeClass("error");
                    }
                    else{
                        $(this).addClass("error");
                        $(this).parent().next().text("※半角数字で入力してください。");
                        $(this).parent().next().addClass("error");
                        if(top_pos == null){
                            top_pos = $(this).offset().top;
                        }
                    }
                }
                    
                else {
                    $(this).addClass("error");
                    $(this).parent().next().text("※年齢を入力してください。");
                    $(this).parent().next().addClass("error");
                    if(top_pos == null){
                        top_pos = $(this).offset().top;
                    }
                }
            })

           

            //生年月日
           
            $("select[name='birth_year_representative[]']").each(function(){
                let year = $(this).val();
                let month = $(this).next().val();
                let day = $(this).next().next().val();

                if(year !== "" && month !== "" && day !== "" ){
                    $(this).removeClass("error");
                    $(this).next().removeClass("error");
                    $(this).next().next().removeClass("error");
                    $(this).parent().next().removeClass("error");
                }
                    
                else {
                    $(this).addClass("error");
                    $(this).next().addClass("error");
                    $(this).next().next().addClass("error");
                    $(this).parent().next().text("※生年月日を入力してください。");
                    $(this).parent().next().addClass("error");
                    if(top_pos == null){
                        top_pos =$(this).offset().top;
                    }
                }
            })

           
          


            //お支払方法

            if($("select[name='payment_method']").val() == ''){
                $("select[name='payment_method']").addClass('error');
                $("select[name='payment_method']").next().addClass('error');
                $("select[name='payment_method']").next().text('※お支払い方法を選択してください。');
                if(top_pos == null){
                    top_pos =$("select[name='payment_method']").offset().top;
                }
            }
            else{
                $("select[name='payment_method']").removeClass('error');
                $("select[name='payment_method']").next().removeClass('error');

                
            }

            //予約者への質問

            if($("textarea[name='answer']").attr('required') != null){
                if($("textarea[name='answer']").val() == ''){
                    $("textarea[name='answer']").addClass('error');
                    $("textarea[name='answer']").next().addClass('error');
                    $("textarea[name='answer']").next().text('※予約者への質問を入力してください。');
                    if(top_pos == null){
                        top_pos = $("textarea[name='answer']").offset().top;
                    }
                }
                else{
                    $("textarea[name='answer']").removeClass('error');
                    $("textarea[name='answer']").next().removeClass('error');
                }
            }

            if($("input[name='agree']").is(':checked') == false){
                $("input[name='agree']").addClass("error");
                if(top_pos == null){
                    top_pos = $("input[name='agree']").offset().top;
                }
            }
            else{
                $("input[name='agree']").removeClass("error");

            }


            let submit_flag = true;

            if(top_pos != null){
                $('html, body').animate({
                    scrollTop: top_pos - 150
                }, 1000);
                submit_flag = false;

            }
            else{
                let number = 0;
                $(".number").each(function(){
                    if($(this).val() != ''){
                        number += parseInt($(this).val());
                    }
                });
                if(number != parseInt($("input[name='limit_number']").val())){
                    alert("参加人数と予約数が一致しません。");
                    submit_flag = false;
                    $('html, body').animate({
                        scrollTop:  350
                    }, 1000);
                }
                else{
                    submit_flag = true;

                }
            }

            if(submit_flag == true){
                $('form.reserveCont').submit();
            }

        
        })  ;
        
    </script>
</body>
</html>
