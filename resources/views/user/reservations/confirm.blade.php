
<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon2_2.ico') }}" />

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('libs/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/add.css') }}">

    <!-- javascript -->
    <script src="{{ asset('libs/jquery/jquery-3.4.1.min.js') }}"></script>
</head>

<body>
    <header class="page-header">
        <div class="header-inner">
            <a href="/" class="logo"><img src="{{ asset('assets/img/logo3.png') }}" alt="" /></a>
            <a href="javascript:void(0)" class="nav-open"><i></i><span></span></a>
            <div class="nav-wrapper">
                <ul class="nav">
                    <li><a href="/">トップ</a></li>
                    <li><a href="/category/news">トピックス</a></li>
                    <li><a href="{{ url('list.php') }}">ツアー情報</a></li>
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
                        <li>@if(request('is_request') == 0) 予約完了 @else リクエスト受付完了  @endif</li>
                    </ul>
                    <div class="reserveBox">
                        <h3 class="reserveBoxHd">予約内容確認</h3>
                        <form action="{{ url('reservations/store') }}" method="post"  class="reserveCont" >

                        @foreach($_POST as $key =>$value)
                            @if(is_array($value))
                                @foreach($value as $key2 =>$value2)
                                <input type="hidden" name="{{$key}}[]"  value="{{$value2}}">
                                @endforeach
                            @else
                            <input type="hidden" name="{{$key}}"  value="{{$value}}">
                            @endif
                        @endforeach

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
                                    <th
                                        rowspan="0"
                                        >料金</th>
                                        @php
                                        $byDay = ['a','b','c','d','e','f','g','h','i','j','k','l'];



                                        if(isset($prices)){
                                            echo '<tr><td>';

                                            for ($i=0; $i<count($prices); $i++ ){
                                                for($k=0;$k<count($byDay);$k++){
                                                    echo '<div class="numberP">';

                                                    if(array_key_exists(sprintf('type%d_%s_%d_number', $prices_types,$byDay[$k],$i+1), $info)){
                                                        echo $prices_name[$i] . '/'.$prices[$i] .'円';
                                                        echo '<p>'. $info[sprintf('type%d_%s_%d_number', $prices_types,$byDay[$k],$i+1)] .'人</p>';
                                                    }
                                                    echo '</div>';
                                                }
                                            }
                                            echo '</td></tr>';

                                        }

                                        echo '<input type="hidden" name="price_type" class="number" value=" '.$info['price_type'].'">';

                                        @endphp

                                </table>
                            </div>
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">申込者情報</h4>
                                <table class="reserveTable">
                                    <tr class="nameTr">
                                        <th>申込者氏名(漢字)</th>
                                        <td>
                                           {{$info['name_last']}} {{$info['name_first']}}
                                        </td>
                                    </tr>
                                    <tr class="nameTr">
                                        <th>申込者氏名(カナ)</th>
                                        <td>
                                            {{$info['kana_last']}} {{$info['kana_first']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>性別</th>
                                        <td>
                                            @if($info['radio_sex'] == 0)
                                            男性
                                            @else
                                            女性
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>生年月日</th>
                                        <td>
                                            <div class="dateP">
                                                {{$info['birth_year']}}年{{ $info['birth_month']}}月{{ $info['birth_day']}}日
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス</th>
                                        <td>
                                            {{$info['email']}}
                                        </td>
                                    </tr>
                                    <tr class="zipTr">
                                        <th class="topTh">住所</th>
                                        <td>
                                            <p>〒{{$info['postalcode']}}</p>
                                            <p>{{$info['prefecture']}} {{$info['address']}}</p>
                                            <p>{{$info['extended']}}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>電話番号</th>
                                        <td>
                                            {{$info['tel']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>緊急連絡先</th>
                                        <td>
                                             {{$info['tel2']}}
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            <div class="reserveItem">
                                <h4 class="reserveItemHd">旅行参加者情報</h4>
                                <div class="reserveList">
                                    <table class="reserveTable">
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(漢字)</th>
                                            <td>
                                                {{$info['add_lastname'][0]}} {{$info['add_firstname'][0]}}
                                            </td>
                                        </tr>
                                        <tr class="nameTr">
                                            <th>参加者(代表者)氏名(カナ)</th>
                                            <td>
                                                {{$info['join_kana1'][0]}} {{$info['join_kana2'][0]}}
                                            </td>
                                        </tr>
                                        <tr class="ageTr">
                                            <th>年齢</th>
                                            <td>
                                                {{$info['join_age'][0]}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>生年月日</th>
                                            <td>
                                                {{$info['birth_year_representative'][0]}}年{{ $info['birth_month_representative'][0]}}月{{ $info['birth_day_representative'][0]}}日
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>性別</th>
                                            <td>
                                                @if($info['join_sex'][0] == 0)
                                                男性
                                                @else
                                                女性
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="zipTr">
                                            <th class="topTh">住所</th>
                                            <td>
                                                <p>〒{{$info['postalcode_representative']}}</p>
                                                <p>{{$info['prefecture_representative']}}</p>
                                                <p>{{$info['address_representative']}} {{$info['extended_representative']}}</p>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>電話番号</th>
                                            <td>
                                                {{$info['tel_representative']}}
                                            </td>
                                        </tr>

                                        @if( isset($info['boarding'][0]) && $info['boarding'][0] != null)
                                        <tr>
                                            <th>乗車地</th>
                                            <td>
                                                {{ $info['boarding'][0]}}
                                            </td>
                                        </tr>
                                        @endif

                                        @if(isset($info['drop'][0]) && $info['drop'][0] != null)
                                        <tr>
                                            <th>降車地</th>
                                            <td>
                                                {{ $info['drop'][0]}}
                                            </td>
                                        </tr>
                                        @endif

                                    </table>
                                    @for($i=1 ; $i<count($info['add_lastname']); $i++)
                                    <table class="reserveTable" style="margin-top:20px;">
                                         <tr class="nameTr">
                                            <th>同行者氏名(漢字)</th>
                                            <td>
                                                {{$info['add_lastname'][$i]}} {{$info['add_firstname'][$i]}}
                                            </td>
                                        </tr>
                                       <tr class="nameTr">
                                            <th>同行者氏名(カナ)</th>
                                            <td>
                                                {{$info['join_kana1'][$i]}} {{$info['join_kana2'][$i]}}
                                            </td>
                                        </tr>
                                        <tr class="ageTr">
                                            <th>年齢</th>
                                            <td>
                                                {{$info['join_age'][$i]}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>生年月日</th>
                                            <td>
                                                {{$info['birth_year_representative'][$i]}}年{{ $info['birth_month_representative'][$i]}}月{{ $info['birth_day_representative'][$i]}}日
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>性別</th>
                                            <td>
                                                @if($info['join_sex'][$i] == 0)
                                                男性
                                                @else
                                                女性
                                                @endif
                                            </td>
                                        </tr>

                                        <tr class="zipTr">
                                            <th class="topTh">住所</th>
                                            <td>
                                                <p>〒{{$info['companion_postalCode'][$i-1]}}</p>
                                                <p>{{$info['companion_prefecture'][$i-1]}}</p>
                                                <p>{{$info['companion_address'][$i-1]}} {{$info['companion_extended'][$i-1]}}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>電話番号</th>
                                            <td>
                                                {{$info['companion_telephone'][$i-1]}}
                                            </td>
                                        </tr>
                                        @if( isset($info['drop'][$i]) && $info['boarding'][$i] != null)
                                        <tr>
                                            <th>乗車地</th>
                                            <td>
                                                {{ $info['boarding'][$i]}}
                                            </td>
                                        </tr>
                                        @endif
                                        @if( isset($info['drop'][$i]) && $info['drop'][$i] != null)
                                        <tr>
                                            <th>降車地</th>
                                            <td>
                                                {{ $info['drop'][$i]}}
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                    @endfor
                                </div>
                            </div>




                            <div class="reserveItem">
                                <h4 class="reserveItemHd">料金決済</h4>
                                <table class="reserveTable">
                                    <tr>
                                        <th>お支払方法</th>
                                        <td>
                                            @if($info['payment_method'] == \App\Constants\PaymentMethodConstants::SPOT)
                                                現地払い
                                            @elseif($info['payment_method'] == \App\Constants\PaymentMethodConstants::PREPAY)
                                                銀行振込
                                            @elseif($info['payment_method'] == \App\Constants\PaymentMethodConstants::CVS)
                                                コンビニ決済
                                            @elseif($info['payment_method'] == \App\Constants\PaymentMethodConstants::CARD)
                                                クレジットカード決済
                                            @endif
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
                                        <th>集合日時</th>
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
{{ date('Y年m月d日', strtotime($date)) }}
                                    </tr>
                                    <tr>
                                        <th>目的地</th>
                                        {{-- <td><strong class="place-placeholder"></strong><br />{{ $plan->place_name }}
〒{{ $plan->place_postalcode }}{{ $plan->place_prefecture }}{{ $plan->place_address }}

アクセス：{{ $plan->place_access }}</td> --}}

<td><strong class="place-placeholder">{{ $plan->destination }}</td>
                                    </tr>
                                    <tr>
                                        <th>キャンセル締切</th>
                                        <td>{{ $plan->deadline }}</td>
                                    </tr>
                                </table>
                            </div>



                            @if($plan->question_flag != 0)
							@php
								$contents = json_decode($plan->question_content , true);
								$answers = json_decode($info['answer'] , true);
							@endphp
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">予約者への質問</h4>
                                <div class="reserveTxt">
									@for ($i = 0; $i < count($contents); $i++)
                                    <p>Q : {{$contents[$i]}}</p>
                                    <p>A : {{$answers[$i]}}</p>
									@endfor
                                </div>
                            </div>
                            @endif



                            @if($info['remark'] != null)
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">備考欄</h4>
                                <div class="reserveTxt">
                                    <p>{{$info['remark']}}</p>
                                </div>
                            </div>
                            @endif
                            @if($plan->caution_content != null)
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">注意事項・その他</h4>
                                <div class="reserveTxt">
                                    <p>{!! $plan->caution_content !!}</p>
                                </div>
                            </div>
                            @endif
                            <div class="reserveItem">
                                <h4 class="reserveItemHd">キャンセル規定</h4>
                                <div class="reserveTxt">
                                    <p>{!! $plan->cancel !!}</p>
                                </div>

                                <p align="center" class="kyoka">※当社からのメールが受信できるようにドメイン指定受信で「@ nagaden-kanko.com」を許可するように設定してください。</p>

                            </div>

                            <ul class="reserveButton">
                                <li><button class="btnLink01" type="submit">@if(request('is_request') == 0)予約する@else リクエスト受付する @endif</button></li>
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
                    <a href="/" class="logo syamei_footer_logo"><img src="{{ asset('assets/img/logo3.png') }}" alt="" /></a>
                    <div class="company-info">
                        <!-- <p class="company-name">長野電鉄株式会社</p> -->
                        <p class="post">〒383-0021</p>
                        <p class="address">長野県中野市西1-1-1<br>信州中野駅１階<br class="sp"></p>
                        <div>
                            <p class="open"></p>
                            <p class="tel-mail"><a href="tel:0269224705">TEL：0269-22-4705 </a> <a href="mailto:"></a></p>
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
                    <span class="menu"><a href="/category/news">トピックス</a></span>
                    <span class="menu"><a href="/contact">お問い合わせ</a></span>
                    <span class="menu"><a href="{{ url('list.php') }}">ツアー情報</a></span>
                    <span class="menu"><a href="/agreement">旅行業約款</a></span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="copyright">Copyright © 長野電鉄株式会社 All Rights Reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('libs/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
</body>
</html>
