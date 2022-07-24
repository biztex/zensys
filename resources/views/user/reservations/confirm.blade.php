
<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="http://153.127.31.62/zenryo/public/assets/img/favicon2_2.ico" />

    <!-- css -->
    <link rel="stylesheet" href="http://153.127.31.62/zenryo/public/libs/slick/slick.css">
    <link rel="stylesheet" href="http://153.127.31.62/zenryo/public/libs/slick/slick-theme.css">
    <link rel="stylesheet" href="http://153.127.31.62/zenryo/public/assets/css/theme.css">
    <link rel="stylesheet" href="http://153.127.31.62/zenryo/public/assets/css/add.css">

    <!-- javascript -->
    <script src="http://153.127.31.62/zenryo/public/libs/jquery/jquery-3.4.1.min.js"></script>
</head>

<body>
    <header class="page-header">
        <div class="header-inner">
            <a href="/" class="logo"><img src="assets/img/logo3.png" alt="" /></a>
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
                        <li>予約内容入力</li>
                        <li class="is_active">予約内容確認</li>
                        <li>予約完了</li>
                    </ul>
                    <div class="reserveBox">
                        <h3 class="reserveBoxHd">予約内容確認</h3>
                        <form action="{{ url('reservations/store') }}" method="post"  class="reserveCont" >

                        @foreach($_POST as $key =>$value)
                            @if(is_array($value))
                                @foreach($value as $key2 =>$value2)
                                <input type="hidden" name="{{$key2}}"  value="{{$value2}}">
                                @endforeach
                            @else
                            <input type="hidden" name="{{$key}}"  value="{{$value}}">
                            @endif
                        @endforeach

                        <div class="reserveItem">
                                <h4 class="reserveItemHd">予約プラン名</h4>
                                <dl class="reserveDl01">
                                    {{-- <dt><img src="{{config('app.url')}}uploads/{{ $plan->file_path1 }}" /></dt> --}}
                                    <dd>
                                        {{-- <p class="nameP"><?//= htmlspecialchars(
                                            //$plan['name']
                                        //) ?></p>
                                        <p class="txtP"><span>実施会社：</span><?//= /$plan[
                                            //'company_name'
                                        //] ?></p>
                                        <p class="txtP"><span>出発日：</span>{{ date('Y年m月d日', strtotime($date)) }}</p> --}}
                                    </dd>
                                </dl>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">予約数</h4>
                                <table class="reserveTable">
                                    <tr>
                                    <th
                                        {{-- @if($stock_price_types){
                                        rowspan="{{count($stock_price_types)}}"
                                        @else
                                        rowspan="{{count($stock_price_types)}}"
                                        @endif --}}

                                        >料金</th>
                                        @php
/*if($stock_price_types){
    foreach ($stock_price_types as $i => $stock_price_type) {
        echo ' <tr><td><div class="numberP">';



            echo $stock_price_type->price_types->name . " / " . number_format($stock_price_type['price']) . " 円";


        if ($i == 0) {
            echo '<p><input type="text" name="type' . $stock_price_type->price_type_number . '_number" class="number" min="0" max="999" value="1"> 人</p>';
        } else {
            echo '<p><input type="text" name="type' . $stock_price_type->price_type_number . '_number" class="number" min="0" max="999" value="0"> 人</p>';
        }
        echo '</div></td></tr>';
    }
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
            echo '<p>2 人</p>';
        } else {
            echo '<p>2 人</p>';
        }
        echo '</div></td></tr>';
    }

}
*/
@endphp
                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">申込者情報</h4>
                                <table class="reserveTable">
                                    <tr class="nameTr">
                                        <th>申込者氏名(漢字)</th>
                                        <td>
                                           {{-- {{$request->name_last}} {{$request->name_first}} --}}
                                           サンプル
                                        </td>
                                    </tr>
                                    <tr class="nameTr">
                                        <th>申込者氏名(カナ)</th>
                                        <td>
                                        {{-- {{$request->kana_last}} {{$request->kana_first}} --}}
                                        サンプル
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>性別</th>
                                        <td>
                                            男性
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>生年月日</th>
                                        <td>
                                            <div class="dateP">
                                            {{-- {{$request->birth_year}}年{{$request->birth_month}}月{{$request->birth_day}}日 --}}
                                            2022年1月1日
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス</th>
                                        <td>
                                        {{-- {{$request->email}} --}}
                                        sample@sample.com
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <th>メールアドレス確認</th>
                                        <td>
                                            sample@exapmle.com
                                        </td>
                                    </tr> -->
                                    <tr class="zipTr">
                                        <th class="topTh">住所</th>
                                        <td>
                                            {{-- <p>〒{{$request->postalcode}}</p>
                                            <p>{{$request->prefecture}} 市区{{$request->address}}</p> --}}
                                            <!-- <p>番地、アパート、マンション名等</p> -->
                                            <p>〒0001111</p>
                                            <p>愛媛県 市区郡町村サンプル</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>電話番号</th>
                                        <td>
                                        {{-- {{$request->tel}} --}}
                                        00011112222
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>緊急連絡先</th>
                                        <td>
                                        {{-- {{$request->tel2}} --}}
                                        00011112222
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">旅行参加者情報</h4>
                                <div class="reserveList">
                                    {{-- @foreach($request->join_kana1 as $key =>$join_kana1)
                                    <table class="reserveTable">
                                        <tr class="nameTr">
                                            <th>申し込み者氏名カナ</th>
                                            <td>
                                                {{$join_kana1}} {{$request->join_kana2[$key]}}
                                            </td>
                                        </tr>
                                        <tr class="ageTr">
                                            <th>年齢</th>
                                            <td>
                                            {{$request->join_age[$key]}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>性別</th>
                                            <td>
                                                @if($request->join_sex[$key]==1)男性
                                                @else 女性 @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>乗車地</th>
                                            <td>
                                            {{$request->join_from[$key]}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>降車地</th>
                                            <td>
                                            {{$request->join_to[$key]}}
                                            </td>
                                        </tr>
                                    </table>
                                    @endforeach --}}

                                    <table class="reserveTable">
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(漢字)</th>
                                            <td>
                                                サンプル
                                            </td>
                                        </tr>
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(カナ)</th>
                                            <td>
                                                サンプル
                                            </td>
                                        </tr>
                                        <tr class="ageTr">
                                            <th>年齢</th>
                                            <td>
                                            40
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>生年月日</th>
                                            <td>
                                                サンプル
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>性別</th>
                                            <td>
                                                男性
                                            </td>
                                        </tr>
                                        <tr class="zipTr">
                                            <th class="topTh">住所</th>
                                            <td>
                                                <p>〒0001111</p>
                                                <p>愛媛県 市区郡町村サンプル</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>電話番号</th>
                                            <td>
                                            00011112222
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>乗車地</th>
                                            <td>
                                            乗車地1
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>降車地</th>
                                            <td>
                                            1
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">料金決済</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th>お支払方法</th>
                                        {{-- <td>
                                        @if($request->payment_method==0)
                                        現地払い
                                        @elseif($request->payment_method==1)
                                        事前払い

                                        @elseif($request->payment_method==2)
                                        事前コンビニ決済
                                        @elseif($request->payment_method==3)
                                        事前クレジットカード決済
                                         @endif
                                        </td> --}}
                                        <td>
                                        現地払い
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">プラン内容</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th>実施会社</th>
                                        {{-- <td>{{ $plan->company_name }}</td> --}}
                                        <td>サンプル会社</td>
                                    </tr>
                                    <tr>
                                        <th>プラン</th>
                                        {{-- <td>{{ $plan->name }}</td> --}}
                                        <td>サンプルプラン</td>
                                    </tr>
                                    <tr>
                                        <th>集合日時/集合場所</th>
                                        {{-- <td>@if ($plan->meeting_point_flag == 1 && $plan->meeting_point_name)<strong class="meeting-point-placeholder"></strong><br />{{ $plan->meeting_point_name }}
〒{{ $plan->meeting_point_postalcode }}
{{ $plan->meeting_point_prefecture }}{{ $plan->meeting_point_address }}

アクセス：{{ $plan->meeting_point_access }}
@elseif ($plan->meeting_point_flag == 2)
@else
{{ $plan->place_name }}
〒{{ $plan->place_postalcode }}
{{ $plan->place_prefecture }}{{ $plan->place_address }}

アクセス：{{ $plan->place_access }}
@endif</td> --}}

<td>
〒1112222
愛媛県 松山市

アクセス：サンプル</td>
                                    </tr>
                                    <tr>
                                        <th>目的地</th>
                                        {{-- <td><strong class="place-placeholder"></strong><br />{{ $plan->place_name }}
〒{{ $plan->place_postalcode }}{{ $plan->place_prefecture }}{{ $plan->place_address }}

アクセス：{{ $plan->place_access }}</td> --}}

<td><strong class="place-placeholder"></strong><br />サンプルプラン
〒9991111北海道サンプル

アクセス：サンプルアクセス</td>
                                    </tr>
                                    <tr>
                                        <th>キャンセル締切</th>
                                        {{-- <td>{{ $plan->cancel_date }}</td> --}}
                                        <td>サンプル</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">注意事項・その他</h4>
                                <div class="reserveTxt">
                                    {{-- <p>{{ $plan->caution_content }}</p> --}}
                                    <p>サンプル</p>
                                </div>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">キャンセル規定</h4>
                                <div class="reserveTxt">
                                    <p>{!! $plan->cancel !!}</p>
                                </div>

                            </div>
                            <ul class="reserveButton">
                                <li><button class="btnLink01" type="submit">予約する</button></li>
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
                    <a href="/" class="logo syamei_footer_logo"><img src="assets/img/logo3.png" alt="" /></a>
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

    <script src="http://153.127.31.62/zenryo/public/libs/slick/slick.min.js"></script>
    <script src="http://153.127.31.62/zenryo/public/assets/js/theme.js"></script>
</body>
</html>
