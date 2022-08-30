<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://zenryo.zenryo-ec.info/assets/img/favicon2_2.ico" />

    <!-- css -->
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/libs/slick/slick.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/libs/slick/slick-theme.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/assets/css/theme.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/assets/css/add.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/vendor/adminlte/dist/css/adminlte.css">

    <!-- javascript -->
    <script src="https://zenryo.zenryo-ec.info/libs/jquery/jquery-3.4.1.min.js"></script>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
</head>

<body>
    <header class="page-header">
        <div class="header-inner">
            <a href="/" class="logo"><img src="https://zenryo.zenryo-ec.info/assets/img/logo3.png" alt="" /></a>
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
                <div class="section__content">
                    <ul class="stepUl">
                        <li class="is_active">予約内容入力</li>
                        <li>予約内容確認</li>
                        <li>予約完了</li>
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
                                                        <p><input type="text" name="<?php echo 'type' . $priceType['number'] .'_'. strtolower($stock['rank']) .'_' . $i . '_number'?>" class="number" min="0" max="'.$stock->limit_number.'" value="{{old($str)}}"> 人</p>
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
                                                <input class="midIpt" id="name_last" type="text"name="name_last" value="{{ old('name_last') }}" required>
                                            </div>
                                            <div class="halfP">
                                                <span>名</span>
                                                <input class="midIpt" id="name_first" type="text" name="name_first" value="{{ old('name_first') }}" required>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="nameTr">
                                        <th>申込者氏名(カナ)<span class="requiredRed">※</span></th>
                                        <td>
                                            <div class="halfP">
                                                <span>セイ</span>
                                                <input class="midIpt" type="text" id="kana_last" name="kana_last" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*"  value="{{ old('kana_last') }}" required>
                                            </div>
                                            <div class="halfP">
                                                <span>メイ</span>
                                                <input class="midIpt" type="text" id="kana_first" name="kana_first" pattern="(?=.*?[\u30A1-\u30FC])[\u30A1-\u30FC\s]*" value="{{ old('kana_first') }}" required>
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
                                                <div class="ageP">
                                                    <input class="midIpt" type="text" name="age" value="{{ old('age')}}" required>
                                                    <span>才</span>
                                                </div>
                                            </td>
                                        </tr>
                                    <tr>
                                        <th>生年月日<span class="requiredRed">※</span></th>
                                        <td>
                                            <div class="dateP">
                                            <select name="birth_year">@for ($i = (date('Y') - 150) ; $i <= (date('Y')) ; $i++) <option value="{{ $i }}" @if ($i == old('birth_year')) selected @endif>{{ $i }}</option> @endfor</select> 年　<select name="birth_month">@for ($i = 1 ; $i <= 12 ; $i++) <option value="{{ $i }}" @if ($i == old('birth_month')) selected @endif >{{ $i }}</option> @endfor</select> 月　<select name="birth_day" >@for ($i = 1 ; $i <= 31 ; $i++) <option value="{{ $i }}"  @if ($i == old('birth_day')) selected @endif >{{ $i }}</option> @endfor</select> 日
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス<span class="requiredRed">※</span></th>
                                        <td>
                                            <input type="text" name="email" id="email" class="width-half" value="{{ old('email') }}" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス確認<span class="requiredRed">※</span></th>
                                        <td>
                                            <input class="width-half form-control" type="email" name="email_confirmation" id="email-confirm" value="{{ old('email_confirmation') }}" onblur="confirmEmail()"  required>
                                            <p id="confAlert" class="requiredRed"></p>
                                        </td>
                                    </tr>
                                    <tr class="zipTr h-adr">
                                        <th class="topTh">住所<span class="requiredRed">※</span></th>
                                        <td>
                                            <span class="p-country-name" style="display:none;">Japan</span>
                                            <div class="zipP01">
                                                <span>〒</span>
                                                <input class="midIpt p-postal-code" type="text" name="postalcode" pattern="\d{3}-?\d{4}" value="{{ old('postalcode') }}" placeholder="ハイフンなしで入力" required>
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
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>緊急連絡先<span class="requiredRed">※</span></th>
                                        <td>
                                            <input class="" type="text" name="tel2" pattern="\d{2,4}-\d{2,4}-\d{3,4}" value="{{ old('tel2') }}" required placeholder="ハイフンありで入力">
                                        </td>
                                    </tr>
                                    <tr class="par_boarding">
                                            <th>乗車地@if($plan->boarding_type == 1)<span class="requiredRed">※</span>@endif</th>
                                            <td>
                                                <select name="planBoarding"  @if($plan->boarding_type == 1) required @endif class="helperWidthMedium">
                                                    @foreach($pieces as $piece)
                                                        <option value="{{$piece}}" @if(old('planBoarding') == $piece) selected @endif>{{$piece}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr  class="par_drop">
                                            <th>降車地@if($plan->drop_type == 1)<span class="requiredRed">※</span>@endif</th>
                                            <td>
                                                <select name="planDrop" class="helperWidthMedium" @if($plan->drop_type == 1) required @endif>
                                                    @foreach($drops as $drop)
                                                        <option value="{{$drop}}" @if(old('planDrop') == $drop) seleted @endif>{{$drop}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
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

                                    
                                    $(".reserveAdd").before('<div class="reserveList helperNone'+ count +'" id="reserveList"></div>')
                                    $(".reserveList.helperNone"+count).load('{{config('app.url')}}html/reservation-companion.php' , function(e){

                                        $data_boarding = $(".par_boarding").children().clone();
                                        $data_drop = $(".par_drop").children().clone();
                                        $(this).find('.child_boarding').append($data_boarding);
                                        $(this).find('.child_drop').append($data_drop);
                                    });

                                    @if($plan->max_number != null)
                                        if(comVal  >= {{$plan->max_number}}){
                                            $(".reserveAdd").css("opacity" , "0");
                                            $(".reserveAdd").css("visibility" , "hidden");
                                        }
                                    @endif

                                    
                                    
                                }

                                $(document).on('click','.reserveDelete',function(){
                                    count--;
                                    $("input[name='limit_number']").val(count);
                                    let comVal = parseInt($("input[name='limit_number']").val());

                                    @if($plan->max_number != null)
                                        if(comVal  < {{$plan->max_number}}){
                                            $(".reserveAdd").css("opacity" , "1");
                                            $(".reserveAdd").css("visibility" , "visible");
                                        }
                                    @endif
                                });

                                function confirmEmail() {
                                    var email = document.getElementById("email").value
                                    var confemail = document.getElementById("email-confirm").value
                                    var confemailAlert = document.getElementById("confAlert");
                                    if(email != confemail) {
                                        confemailAlert.innerHTML = "メールアドレスと確認用メールアドレスが一致しません";
                                    }
                                    else{
                                        confemailAlert.innerHTML = "";

                                    }
                                }
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
                                                    <input class="midIpt" type="text" name="add_lastname[]" value="{!! old('add_lastname.0') !!}" required>
                                                </div>
                                                <div class="halfP">
                                                    <span>名</span>
                                                    <input class="midIpt" type="text" name="add_firstname[]" value="{{ old('add_firstname.0') }}" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(カナ)<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="halfP">
                                                    <span>セイ</span>
                                                    <input class="midIpt" type="text" name="join_kana1[]" value="{{ old('join_kana1.0') }}" required>
                                                </div>
                                                <div class="halfP">
                                                    <span>メイ</span>
                                                    <input class="midIpt" type="text" name="join_kana2[]" value="{{ old('join_kana2.0') }}" required>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="ageTr">
                                            <th>年齢<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="ageP">
                                                    <input class="midIpt" type="text" name="join_age[]"  value="{{ old('join_age.0') }}" required>
                                                    <span>才</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                        <th>生年月日<span class="requiredRed">※</span></th>
                                            <td>
                                                <div class="dateP">
                                                <select name="birth_year_representative[]">@for ($i = (date('Y') - 150) ; $i <= (date('Y')) ; $i++) <option value="{{ $i }}" @if ($i == old('birth_year_representative.0')) selected @endif>{{ $i }}</option> @endfor</select> 年　<select name="birth_month_representative[]">@for ($i = 1 ; $i <= 12 ; $i++) <option value="{{ $i }}" @if ($i == old('birth_month_representative.0')) selected @endif >{{ $i }}</option> @endfor</select> 月　<select name="birth_day_representative[]">@for ($i = 1 ; $i <= 31 ; $i++) <option value="{{ $i }}"  @if ($i == old('birth_day_representative.0')) selected @endif >{{ $i }}</option> @endfor</select> 日
                                                </div>
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
                                                <div class="zipP01">
                                                    <span>〒</span>
                                                    <input class="midIpt p-postal-code" type="text" name="postalcode_representative" pattern="\d{3}-?\d{4}" value="{{ old('postalcode_representative') }}" placeholder="ハイフンなしで入力" required>
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
                                        <tr class="par_boarding">
                                            <th>乗車地@if($plan->boarding_type == 1)<span class="requiredRed">※</span>@endif</th>
                                            <td>
                                                <select name="boarding[]" @if($plan->boarding_type == 1) required @endif class="helperWidthMedium">
                                                    @foreach($pieces as $piece)
                                                        <option value="{{$piece}}" @if(old('boarding.0') == $piece) selected @endif>{{$piece}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr  class="par_drop">
                                            <th>降車地@if($plan->drop_type == 1)<span class="requiredRed">※</span>@endif</th>
                                            <td>
                                                <select name="drop[]" class="helperWidthMedium" @if($plan->drop_type == 1) required @endif>
                                                    @foreach($drops as $drop)
                                                        <option value="{{$drop}}" @if(old('drop.0') == $drop) selected @endif>{{$drop}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <p class="reserveDelete"></p>
                                </div>

                                <div class="reserveList helperNone0" id="reserveList"></div>
                                <input type="hidden" name="limit_number" value="1" />

                                <p class="reserveAdd"><a href="#" class="grayBtn" onclick="addJoin()">同行者情報を追加</a></p>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">料金決済</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th>お支払方法<span class="requiredRed">※</span></th>
                                        <td>
                                            <select name="payment_method" class="midSelect" required >
                                                <option>選択してください</option>
                                                @if ($plan->card == 1)
                                                        <option value="3" @if (old('payment_method') == 3) selected @endif>クレジットカード決済</option>
                                                @endif
                                                @if ($plan->spot == 1)
                                                        <option value="0" @if (old('payment_method') == 0) selected @endif>現地払い</option>
                                                @endif
                                                @if ($plan->prepay == 1)
                                                        <option value="1" @if (old('payment_method') == 1) selected @endif>事前払い</option>
                                                @endif
                                                @if ($plan->cvs == 1)
                                                        <option value="2" @if (old('payment_method') == 2) selected @endif>コンビニ決済</option>
                                                @endif
                                               
                                             </select>
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
                                    <!-- <tr>
                                        <th>集合日時/集合場所</th>
                                        <td>@if ($plan->meeting_point_flag == 1 && $plan->meeting_point_name)<strong class="meeting-point-placeholder"></strong><br />{{ $plan->meeting_point_name }}
                                        〒{{ $plan->meeting_point_postalcode }}
                                        {{ $plan->meeting_point_prefecture }}{{ $plan->meeting_point_address }}

                                        アクセス：{{ $plan->meeting_point_access }}
                                        @elseif ($plan->meeting_point_flag == 2)
                                        @else
                                        {{ $plan->place_name }}
                                        〒{{ $plan->place_postalcode }}
                                        {{ $plan->place_prefecture }}{{ $plan->place_address }}

                                        アクセス：{{ $plan->place_access }}
                                        @endif</td>
                                    </tr> -->
                                    <tr>
                                        <th>目的地</th>
                                        <td>{{ $plan->destination }}
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <th>キャンセル締切</th>
                                        <td>{{ $plan->cancel_date }}</td>
                                    </tr> -->
                                </table>
                            </div>

                            @if($plan->question_flag != 0)
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">予約者への質問</h4>
                                <div class="reserveTxt">
                                    <p>@if($plan->answer_flag == 1)<span class="requiredRed">※</span>@endif {{$plan->question_content}}</p>
                                    <textarea name="answer" class="reserveTextarea" @if($plan->answer_flag == 1) required @endif >{{ old('answer') }}</textarea>
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
                                <input type="checkbox" name="agree" id="agree01">
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

    <script src="libs/slick/slick.min.js"></script>
    <script src="https://zenryo.zenryo-ec.info/assets/js/theme.js"></script>
    <script>
         let comVal_cs = parseInt($("input[name='limit_number']").val());

                               
        if(comVal_cs  >= {{$plan->max_number}}){
            $(".reserveAdd").css("opacity" , "0");
            $(".reserveAdd").css("visibility" , "hidden");
        }

    </script>
</body>
</html>
