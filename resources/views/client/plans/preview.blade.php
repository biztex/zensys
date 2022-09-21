@php
$plan_id = $plans->id;
$url = config('app.url')."api/plan/json/" . $plan_id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$array = curl_exec($ch);
$plan = json_decode($array,true);
$plan = $plan[0];




$min_value = $plan["prices"][0]['a_1'];
$max_value = $plan["prices"][0]['a_1']; 

for($k=0; $k<count($plan["prices"]); $k++){
    $price_t = $plan["prices"][$k];
    $alphas = ['a','b','c','d','e','f','g','h','i','j','k'];
    for($j=0; $j<count($alphas); $j++ ){
        $com_val =  $alphas[$j].'_1';
        if($max_value < $price_t[$com_val]  && $price_t[$com_val] != null ){
            $max_value = $price_t[$com_val]; 
        }
        else if($min_value > $price_t[$com_val] && $price_t[$com_val] != null){
            $min_value = $price_t[$com_val];
        }
       
    }
}

$plan_json = json_encode($array, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$plan_json = str_replace('¥u0022', '¥¥¥"', $plan_json);
$plan_json = str_replace('"', '', $plan_json);


$url = config('app.url')."api/roadMap/json/" . $plan_id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$array = curl_exec($ch);
$roadmaps = json_decode($array,true);


$roadmap_json = json_encode($array, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$roadmap_json = str_replace('¥u0022', '¥¥¥"', $roadmap_json);
$roadmap_json = str_replace('"', '', $roadmap_json);


$url = config('app.url') . "api/price/json/" . $plan_id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$array = curl_exec($ch);
$prices = json_decode($array,true);


$url =  config('app.url') . "api/company/json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$company_array = curl_exec($ch);
$companies = json_decode($company_array, true);


$company = $companies[0];


$priceType = $prices[0]['type'];
$price_type_name=$prices[0]['name'];
if (isset($_GET["year"]) && isset($_GET["month"])) {
    $current_date = new DateTime($_GET["year"] . '-' . $_GET["month"]);
    $current_y = $current_date->format('Y');
    $current_m = $current_date->format('m');
    $next_m_date = $current_date->modify('first day of next month')->modify('last day of');
} else {
    $current_date = new DateTime(substr($plan["start_day"],0,7));
    $current_y = $current_date->format('Y');
    $current_m = $current_date->format('m');
    $next_m_date = $current_date->modify('first day of next month')->modify('last day of');
}
$next_y = $next_m_date->format('Y');
$next_m = $next_m_date->format('m');


$url_stocks = config('app.url') . "api/stocks/json/" . $current_y . '/' . $current_m . '/' . $plan_id.'/'.$priceType ;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url_stocks);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json_stocks = curl_exec($ch);
$stocks = json_decode($json_stocks, true);


$url_stocks_next = config('app.url') . "api/stocks/json/" . $next_y . '/' . $next_m . '/' . $plan_id.'/'.$priceType;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, $url_stocks_next);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json_stocks_next = curl_exec($ch);
$stocks_next = json_decode($json_stocks_next, true);
@endphp
<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{asset'assets/img/favicon2_2.ico')}}" />
    <title>{{htmlspecialchars($plan["name"])}}</title>
    <!-- css -->
    <link rel="stylesheet" href="{{asset('libs/slick/slick.css')}}">
    <link rel="stylesheet" href="{{asset('libs/slick/slick-theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/add.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/print.css')}}" media="print">

    <!-- javascript -->
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <style>
        .road p img{
            display: inline-block;
            vertical-align: middle;
        }
        .road p{
            line-height:4
        }
        .warning{
            position: relative;
            width:60%;
            margin-left:auto;
            margin-right: auto;
            background: white;
            padding: 50px;
            text-align: center;

        }
        .warning h2{
            font-size: 3em;
        }
        .warning a{
            background-color: #DE9A2E;
            display: block;
            width: 100px;
            padding: 15px;
            font-weight: 700;
            font-size: 1.5em;
            color: white;
            margin-top: 80px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>

<header class="page-header">
     <div class="header-inner">
         <a href="/" class="logo"><img src="{{asset('assets/img/logo3.png'}}" alt="" /></a>
         <a href="javascript:void(0)" class="nav-open"><i></i><span></span></a>
         <div class="nav-wrapper">
             <ul class="nav">
                 <li><a href="{{config('app.url')}}">トップ</a></li>
                 <li><a href="{{config('app.url')}}/category/news">新着情報</a></li>
                 <li><a href="{{config('app.url')}}/category/tour">ツアー紹介</a></li>
                 <li><a href="{{config('app.url')}}/company">会社概要</a></li>
                 <li><a href="{{config('app.url')}}/contact">お問い合わせ</a></li>
             </ul>
         </div>
     </div>
 </header>
 
 <main class="page-wrapper">
     <section class="section section--detail section--with-top-margin">
         <div class="inner">
             <div class="section__content">
                 <div class="listItem">
                     <p class="listItemHd">{{htmlspecialchars($plan["name"])}}</p>
                     <div class="listItemCont">
                         <p class="listItemTxt"><?=nl2br(htmlspecialchars($plan["catchphrase"])) ?></p>
                         <div class="listItemInfo">
                             <div class="rightP">
                                 <div class="dtFor">
                                     <div class="dtSlist"><img src="{{asset('upload')}}/<?=$plan["file_path1"] ?>" alt=""></div>
                                     @if($plan["file_path2"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path2"] }}" alt=""></div>@endif
                                     @if($plan["file_path3"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path3"] }}" alt=""></div>@endif
                                     @if($plan["file_path4"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path4"] }}" alt=""></div>@endif
                                     @if($plan["file_path5"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path5"] }}" alt=""></div>@endif
                                     @if($plan["file_path6"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path6"] }}" alt=""></div>@endif
                                     @if($plan["file_path7"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path7"] }}" alt=""></div>@endif
                                     @if($plan["file_path8"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path8"] }}" alt=""></div>@endif
                                     @if($plan["file_path9"])<div class="dtSlist"><img src="{{asset('uploads'}}/{{$plan["file_path9"] }}" alt=""></div>@endif
                                 </div>
                                 <div class="dtNav">
                                 <div class="dtSlist"><img src="{{asset('upload')}}/<?=$plan["file_path1"] ?>" alt=""></div>
                                     @if($plan["file_path2"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path2"] }}" alt=""></div>@endif
                                     @if($plan["file_path3"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path3"] }}" alt=""></div>@endif
                                     @if($plan["file_path4"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path4"] }}" alt=""></div>@endif
                                     @if($plan["file_path5"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path5"] }}" alt=""></div>@endif
                                     @if($plan["file_path6"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path6"] }}" alt=""></div>@endif
                                     @if($plan["file_path7"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path7"] }}" alt=""></div>@endif
                                     @if($plan["file_path8"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path8"] }}" alt=""></div>@endif
                                     @if($plan["file_path9"])<div class="dtSlist"><img src="{{asset('uploads')/{{$plan["file_path9"] }}" alt=""></div>@endif
                                 </div>
                             </div>
                             <div class="leftP">
                                 <div class="messageP">
                                     <dl>
                                         <dt>目的地</dt>
                                         <dd><?=htmlspecialchars($plan["destination"]) ?></dd>
                                     </dl>
                                     <dl>
                                         <dt>旅行日程</dt>
                                         <dd><?=htmlspecialchars($plan["schedule"]) ?></dd>
                                     </dl>
                                     <dl>
                                         <dt>出発日</dt>
                                         <dd>
                                            @if($plan["start_day"] == $plan["end_day"])
                                                {{htmlspecialchars($plan["start_day"]);}} 
                                            @else
                                                {{htmlspecialchars($plan["start_day"])}}～{{htmlspecialchars($plan["end_day"])}}
                                            @endif

                                         </dd>
                                     </dl>
                                     <dl>
                                         <dt>お食事</dt>
                                         <dd>{{htmlspecialchars($plan["eat"])}}</dd>
                                     </dl>
                                     <dl>
                                         <dt>添乗員</dt>
                                         <dd>{{ $plan["conductor_selected"] ? '有' : '無' }}</dd>
                                     </dl>
                                     <dl>
                                         <dt>受付締切日時</dt>
                                         
                                         @if($plan["res_type"] == 0)
                                             <dd>出発日の{{htmlspecialchars($plan["res_end_day"])}}
                                             {{$plan["res_end_type"] == 0 ? '日前' : '週間前'}}</dd>
                                         @else
                                             <dd>出発日の{{htmlspecialchars($plan["req_before_day"])}}{{$plan["req_before_type"] == 0 ?  '日前' : '週間前' }}</dd>
                                         @endif
                                     </dl>
                                     <dl>
                                         <dt>支払方法</dt>
                                         <dd>@if($plan["card"] == 1) クレジットカード / @endif
                                             @if($plan["cvs"] == 1) コンビニ決済 / @endif
                                             @if($plan["prepay"] == 1) 銀行振込 / @endif
                                             @if($plan["spot"] == 1) 現地払い @endif</dd>
                                     </dl>
                                     <dl>
                                         <dt>ツアーコード</dt>
                                         <dd>{{htmlspecialchars($plan["code"])}}</dd>
                                     </dl>
                                     <dl>
                                         <dt>最小催行人員</dt>
                                         <dd>{{htmlspecialchars($plan["min_cnt"])}}</dd>
                                     </dl>
                                     @if($plan["institution"])
                                     <dl>
                                         <dt>利用宿泊施設</dt>
                                         <dd>{{nl2br(htmlspecialchars($plan["institution"])) }}</dd>
                                     </dl>
                                     @endif
                                     @if($plan["transportation"])
                                     <dl>
                                         <dt>利用交通機関</dt>
                                         <dd>{{nl2br(htmlspecialchars($plan["transportation"]))}}</dd>
                                     </dl>
                                     @endif

                                 </div>
                                 <p class="priceP">旅行代金（お一人様<span>¥{{number_format($min_value) }}〜¥{{number_format($max_value) }}</span></p>
                                 <p class="btnP"><a href="#calendar_area" class="scroll btnLink01">旅行代金カレンダーを見る</a></p>
                             </div>
                         </div>
                         <div class="detailItem">
                             <p class="detailItemHd">プラン情報</p>
                             <div class="reserveTxt">
                                 <p>{{nl2br(htmlspecialchars($plan["description"]))}}</p>
                             </div>
                             <table class="reserveTable">
                                 <tr>
                                     <th>催行期間</th>
                                     <td>@if($plan["start_day"] != $plan["end_day"])
                                         {{nl2br(htmlspecialchars($plan["start_day"])) . '　～　' .  nl2br(htmlspecialchars($plan["end_day"]))}}
                                        @else
                                          {{nl2br(htmlspecialchars($plan["start_day"]))}}
                                        @endif
                                    </td>
                                 </tr>
                                 @if($plan["included_item"])
                                 <tr>
                                     <th>料金に含まれるもの</th>
                                     <td>{{nl2br(htmlspecialchars($plan["included_item"]))}}</td>
                                 </tr>
                                 @endif
                             </table>
                         </div>
                         <div class="detailItem printArea">
                             <p class="detailItemHd">行程表 <a href="javascript:;" onclick="window.print();" class="printBtn">印刷する</a></p>
                             <table class="detailTable">
                                 <colgroup class="pc">
                                     <col width="11.2%">
                                     <col width="50%">
                                     <col width="14.5%">
                                     <col width="22.4%">
                                 </colgroup>
                                 <tbody><tr>
                                     <th>日程</th>
                                     <th>予定</th>
                                     <th>食事</th>
                                     <th>宿泊</th>
                                 </tr>
                                 @foreach ($roadmaps as $key => $value)
                                 <tr>
                                     <td>{!! $value["road_map_title"] !!}</td>
                                     <td class="road">
                                         {!! $value["road_map"] !!}
                                     </td>
                                     <td>朝：{{$value["road_eat1"] == 1 ? "あり":"なし"}}
                                         <br>
                                         昼：{{$value["road_eat2"] == 1 ? "あり":"なし"}}
                                         <br>
                                         夕：{{$value["road_eat3"] == 1 ? "あり": "なし"}}
                                     </td>
                                     <td>{!! $value["road_map_build"] !!}</td>
                                 </tr>
                                 @endforeach
                             </tbody></table>
                         
                         
                         </div>
                     </div>
                 </div>
                 <p class="calendarHd" id="calendar_area">旅行代金カレンダー</p>
             </div>
         </div>
     </section>
     <section class="section section_calendar" id="anchor-calendar">
         <div class="inner">
             <div class="section__content">
                 <div class="dateArea">
                     <div class="timeSelect">
                         <span>料金区分選択</span>
                         <select name="price_type_id" id="submit_select2">
                             @foreach ($prices  as $key => $value)
                                 <option value="{{$value['type']}}">{{$value['name']}}</option>
                             @endforeach

                         </select>
                     </div>
                     <div class="dateBox">
                         <div class="dateList">
                             <div class="dateItems">
                                 <div class="dateHd">
                                     <a href="#" class="prevM disabled">◀︎ 前月</a>
                                     <a href="#" class="nextM">翌月 ▶︎</a>
                                 </div>
                             </div>
                             <div class="dateItems is_active">
                                 <div class="dateHd">
                                     <a href="#" class="prevM prev-month">◀︎ 前月</a>
                                     <a href="#" class="nextM next-month">翌月 ▶︎</a>
                                 </div>
                                 <table class="dateTable">
                                     <thead>
                                         <tr>
                                             <td colspan="7">
                                                 @php
                                                     foreach ($stocks['dates'] as $date_next) {
                                                         $current_date = new DateTime(substr($date_next, 0, 10));
                                                         $y = (int)$current_date->format('Y');
                                                         $m = $current_date->format('m');
                                                         $d = $current_date->format('j');
                                                         if ($d == 15) {
                                                             echo $y . '年' . $m . '月';
                                                         }
                                                     }
                                                 @endphp
                                             </td>
                                         </tr>
                                         <tr>
                                             <td>日</td>
                                             <td>月</td>
                                             <td>火</td>
                                             <td>水</td>
                                             <td>木</td>
                                             <td>金</td>
                                             <td>土</td>
                                         </tr>
                                     </thead>
                                     <tbody>
                                     @php
                                         $week = ['日', '月', '火', '水', '木', '金', '土'];
                                         $tmp_arr=["A","B","C","D","E","F","G","H","H","J","K","L"];
                                         $com_date = new DateTimeImmutable(date('Y-m-d'));

                                         foreach ($stocks['dates'] as $date) {


                                             $end =  new DateTimeImmutable($date);
                                             $time = date('H');
                                             $interval = date_diff($end, $com_date);
                                             
                                             if($plan['res_type'] == 0){
                                                 $compare = intval($interval->format('%R%a')) + intval($plan['res_end_day']);
                                             }
                                         
                                             else{
                                                 $compare = intval($interval->format('%R%a')) + intval($plan['req_before_day']);
                                             }
                                             $current_date = new DateTime(substr($date, 0, 10));
                                             $current_date = $current_date->modify("+1 days");
                                             $w = (int)$current_date->format('w');
                                             $d = $current_date->format('j');
                                             if ($w == 0) {
                                                 echo '<tr>';
                                             }
                                             $count = 0;

                                             foreach ($stocks["stocks"] as $stock) {
                                     
                                                 $day_price=$stock["price"];
                                                 if($stock["price"]){
                                                     $day_price=$stock["price"];
                                                     if(is_array($day_price)){
                                                 
                                                         $str="";
                                                         foreach ($day_price as $key => $value) {
                                                             $str=$str.$key.":".$value."----";
                                                         }
                                                         //die($str);
                                                         $day_price = $str;
                                                     }
                                                 }
                                                 if ($current_date->format('Y-m-d') == $stock["res_date"] && $stock["is_active"] == 1 && $compare < 0 && $current_date->format('m') == $current_m) {
                                                     if ($stock["res_type"] == 0) {
                                                         if( $stock["rank"] != null){
                                                             echo '<td class="stock' .$stock["rank"] . '"><p class="dayP">' . $d . '</p>';

                                                             if ($stock["limit_number"] > 0) {
                                                                 foreach ($prices as  $price) {
                                                                     foreach ($tmp_arr as  $al) {
                                                                         if(($price[strtolower($al)."_1"] || $price[strtolower($al)."_2"]) && $al == $stock["rank"]){
                                                                             echo '<a class="selected-date ' . $price['type'] . '" style="cursor:pointer;" data-price='.$price['name'].':¥'.number_format($price[strtolower($al)."_1"]).'>';
                                                                             echo '<p class="datePrice">'.$stock["rank"];
 
 
                                                                             if($price[strtolower($al)."_1"]){
                                                                                 echo '<br>¥'.number_format($price[strtolower($al)."_1"]);
                                                                             }
                                                                             if($price[strtolower($al)."_2"]){
                                                                                 echo '<br><font>(¥'.number_format($price[strtolower($al)."_2"]).")</font>";
                                                                             }
                                                                             if($stock["limit_number"] > 3){
                                                                                 echo '<br>○';
                                                                             }
                                                                             else{
                                                                                 echo '<br>△';
 
                                                                             }
                                                                             echo '</p>';
                                                                             echo '</a><input type="hidden" class="' . $price['type'] . '" value="' . $current_date->format('Y-m-d') . '">';
                                                                         }
 
                                                                     }
                                                                 }
                                                     

                                                     
                                                             
                                                             //	echo '<a class="selected-date" style="cursor:pointer;" data-price='.$price_type_name.':'.$day_price.'>○</a><input type="hidden" value="' . $current_date->format('Y-m-d') . '">';
                                                             } else if ($stock["limit_number"] == 0) {
                                                                 foreach ($prices as  $price) {
                                                                     foreach ($tmp_arr as  $al) {
                                                                         if(($price[strtolower($al)."_1"] || $price[strtolower($al)."_2"]) && $al == $stock["rank"]){
                                                                             echo '<a class="selected-date ' . $price['type'] . ' data-price='.$price['name'].':¥'.number_format($price[strtolower($al)."_1"]).'>';
                                                                             echo '<p class="datePrice">'.$stock["rank"];


                                                                             if($price[strtolower($al)."_1"]){
                                                                                 echo '<br>¥'.number_format($price[strtolower($al)."_1"]);
                                                                             }
                                                                             if($price[strtolower($al)."_2"]){
                                                                                 echo '<br><font>(¥'.number_format($price[strtolower($al)."_2"]).")</font>";
                                                                             }
                                                                         
                                                                             echo '<br>×';
                                                                             echo '</p>';
                                                                             echo '</div>';
                                                                         }

                                                                     }
                                                                 }
                                                             } else {
                                                                 foreach ($prices as  $price) {
                                                                     foreach ($tmp_arr as  $al) {
                                                                         if(($price[strtolower($al)."_1"] || $price[strtolower($al)."_2"]) && $al == $stock["rank"]){
                                                                             echo '<div class="selected-date ' . $price['type'] . ' data-price='.$price['name'].':¥'.number_format($price[strtolower($al)."_1"]).'>';
                                                                             echo '<p class="datePrice">'.$stock["rank"];


                                                                             if($price[strtolower($al)."_1"]){
                                                                                 echo '<br>¥'.number_format($price[strtolower($al)."_1"]);
                                                                             }
                                                                             if($price[strtolower($al)."_2"]){
                                                                                 echo '<br><font>(¥'.number_format($price[strtolower($al)."_2"]).")</font>";
                                                                             }
                                                                             
                                                                             echo '<br>-';
                                                                             echo '</p>';
                                                                             echo '</div>';
                                                                         }

                                                                     }
                                                                 }
                                                             }
                                                         }
                                                         else{
                                                             echo '<td style="background-color: #777">' . $d . '<br />-</td>';
                                                         }
                                                         $count++;
                                                     } else if ($stock["res_type"] == 1) {
                                                         if( $stock["rank"] != null){
                                                         
                                                             echo '<td class="stock' .$stock["rank"] . '"><p class="dayP">' . $d . '</p>';
                                                             echo '<p class="datePrice">B<br>残数：4<br>¥70,000<br><font>(¥40,000)</font></p>';
                                                             if ($stock["limit_number"] > 0) {
                                                                 foreach ($prices as  $price) {
                                                                     foreach ($tmp_arr as  $al) {
                                                                         if($price[strtolower($al)."_1"] || $price[strtolower($al)."_2"]){
                                                                             echo '<a class="selected-date" style="cursor:pointer;" data-price='.$price_type_name.':'.$day_price.'>';
                                                                             echo '<p class="datePrice">'.$al.'<br>残数：'.$stock["limit_number"] .'';

                                                                             if($price[strtolower($al)."_1"]){
                                                                                 echo '<br>¥'.number_format($price[strtolower($al)."_1"]);
                                                                             }
                                                                             if($price[strtolower($al)."_2"]){
                                                                                 echo '<br><font>(¥'.number_format($price[strtolower($al)."_1"]).")</font>";
                                                                             }
                                                                             echo '</p>';
                                                                             
                                                                             echo '</a><input type="hidden" value="' . $current_date->format('Y-m-d') . '">';
                                                                         }

                                                                     }
                                                                 }
                                                             } else if ($stock["limit_number"] == 0) {
                                                             //	echo '<a class="selected-date" style="cursor:pointer;" data-price='.$price_type_name.':'.$day_price.'>□</a><input type="hidden" value="' . $current_date->format('Y-m-d') . '">';
                                                             } else {
                                                             //	echo '-';
                                                             }
                                                         }
                                                         else{
                                                             echo '<td style="background-color: #777">' . $d . '<br />-</td>';
                                                         }
                                                         $count++;
                                                     } else if ($stock["res_type"] == 2) {
                                                         if( $stock["rank"] != null){

                                                             echo '<td class="stock' .$stock["rank"] . '"><p class="dayP">' . $d . '</p>';
                                                             foreach ($prices as  $price) {
                                                                 foreach ($tmp_arr as  $al) {
                                                                     if(($price[strtolower($al)."_1"] || $price[strtolower($al)."_2"]) && $al == $stock["rank"]){
                                                                     
                                                                         echo '<a class="selected-date ' . $price['type'] . '" style="cursor:pointer;" data-price='.$price['name'].':¥'.number_format($price[strtolower($al)."_1"]).'>';
                                                                         echo '<p class="datePrice">'.$stock["rank"];


                                                                         if($price[strtolower($al)."_1"]){
                                                                             echo '<br>¥'.number_format($price[strtolower($al)."_1"]);
                                                                         }
                                                                         if($price[strtolower($al)."_2"]){
                                                                             echo '<br><font>(¥'.number_format($price[strtolower($al)."_2"]).")</font>";
                                                                         }
                                                                         echo '<br> □ ';
                                                                         echo '</p>';
                                                                         echo '</a><input type="hidden" class="' . $price['type'] . '" value="' . $current_date->format('Y-m-d') . '">';
                                                                     }

                                                                 }
                                                             }
                                                         //      echo '<a class="selected-date" style="cursor:pointer;" data-price='.$price_type_name.':'.$day_price.'>□</a><input type="hidden" value="' . $current_date->format('Y-m-d') . '">';
                                                         }
                                                         else{
                                                             echo '<td style="background-color: #777">' . $d . '<br />-</td>';
                                                         }
                                                             $count++;

                                                     }
                                                     echo '</td>';
                                                 }
                                             }
                                             if ($count == 0) {
                                                 echo '<td style="background-color: #777">' . $d . '<br />-</td>';
                                             }
                                             if ($w == 6) {
                                                 echo '</tr>';
                                             }
                                         }
                                         @endphp


                                     </tbody>
                                 </table>
                             </div>
                         
                         </div>
                         <p class="dateType">
                             空き状況： ○ 予約可 / △残り僅か / × 空きなし / □ リクエスト可 / － 受付対象外
                         </p>
                         <div class="reserveTxt">
                             <p>※予約の場合は申し込みと同時に予約確定となります。予約枠ごとに料金が異なる場合がございます。<br>※リクエストが完了しても予約が確定したわけではありません。リクエスト予約は仮予約の状態となります。実施会社の確認連絡をもって予約の確定としています。予めご了承ください。<br>※子供料金はカッコ内に記載。記載がない場合は子供料金の設定はございません。</p>
                         </div>
                         <!-- <p class="detailHd01">基本旅行代金について</p>
                         <div class="reserveTxt">
                             <p>●子供料金の設定はございません。尚、年齢制限はございませんが体力的な面を考慮し、小学生未満のご参加は推奨致しかねます。<br>●旅行代金にはバス代、行程表に明示した食事、宿泊代金、温泉入湯料が含まれます。</p>
                         </div> -->
                         <div class="dtPayment">
                             <p class="detailHd01">お支払方法</p>
                             <table class="reserveTable">
                                 <tr>
                                     <th>お支払方法</th>
                                     <td>@if ($plan["spot"] == 1)
                                            現地払い<br />
                                        @endif
                                        @if ($plan["prepay"] == 1) 
                                            銀行振込<br />
                                        @endif
                                        @if ($plan["cvs"] == 1) 
                                            コンビニ決済<br />
                                        @endif
                                        @if ($plan["card"] == 1) {
                                           クレジットカード決済<br />
                                        @endif
                                    </td>
                                 </tr>
                                 <tr>
                                     <th>お支払方法の補足、詳細</th>
                                     <td>{{$plan["payment_comment"]}}</td>
                                 </tr>
                             </table>
                         </div>

                         <div class="reserve-box" style="display:none;">
                         <input type="hidden" name="res-date"><input type="hidden" name="res-type" value="{{$plan["res_type"]}}">
                         <table class="detailTable">
                             <colgroup class="pc">
                                 <col width="auto">
                                 <col width="auto">
                                 <col width="22.4%">
                             </colgroup>
                             <tr>
                                 <th>出発</th>
                                 <th>料金</th>
                                 <th>予約</th>
                             </tr>
                             <tr class="tcTr">
                                 <td class="res-date"></td>
                                 <td class="price2"></td>
                                 <td><a href="#" class=" btnLink01 reserve-button">予約へ進む</a></td>
                             </tr>
                         </table>
                         </div>
                         <div class="detailItem">
                             <p class="detailItemHd">注意事項・その他</p>
                             <div class="reserveTxt">
                                 <p>{{nl2br(htmlspecialchars($plan['caution_content'])) }}</p>
                             </div>
                         </div>
                         <div class="detailItem">
                             <p class="detailItemHd">キャンセル規定</p>
                             <div class="reserveTxt">
                                 {{$plan['cancel']}}
                             </div>
                             @if($plan['cancel_date']) 
                             <table class="reserveTable">
                                 <tr>
                                     <th>キャンセル締切</th>
                                     <td>{{ htmlspecialchars($plan['cancel_date']) }}</td>
                                 </tr>
                             </table>
                             @endif
                         </div>
                         @if ($plan["file_path11"] != null || $plan['notice'] != null)
                         <div class="detailItem">
                             <p class="detailItemHd">ご旅行条件書</p>
                             <div class="reserveTxt">
                                 
                                 <p>お申込みの際には、必ず<b>
                                     @if($plan["file_path11"] != null)
                                         <a href="{{asset('upload')}}/{{$plan["file_path11"]}}" target="_blank">
                                     @elseif($plan["notice"] != null)
                                         <a href="<?=$plan["notice"]?>" target="_blank">
                                     @endif ご旅行条件書</a></b>をお読みください。</p>
                             
                             </div>
                         </div>
 
                         @endif
                         <div class="detailItem">
                             <p class="detailItemHd">旅行企画</p>
                             <table class="reserveTable">
                                 <tr>
                                     <th>旅行企画・実施会社</th>
                                     <td>{{$company["name"]}}</td>
                                 </tr>
                                 <tr>
                                     <th>旅行業登録番号</th>
                                     <td>{{$company["company_number"]}}</td>
                                 </tr>
                                 <tr>
                                     <th>住所</th>
                                     <td>{{$company["address"]}}</td>
                                 </tr>
                             </table>
                         </div>
                         <p class="detailBtn"><a href="#calendar_area" class="scroll btnLink01">ご予約・お見積りはこちらの旅行代金カレンダーのご希望出発日をクリック</a></p>
                     </div>
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
                 <a href="{{config('app.url')}}" class="logo syamei_footer_logo"><img src="{{asset('assets/img/logo3.png')}}" alt="" /></a>
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
                 <span class="menu"><a href="{{config('app.url')}}">トップ</a></span>
                 <span class="menu"><a href="{{config('app.url')}}/company">会社概要</a></span>
                 <span class="menu"><a href="{{config('app.url')}}/category/news">新着情報</a></span>
                 <span class="menu"><a href="{{config('app.url')}}/contact">お問い合わせ</a></span>
                 <span class="menu"><a href="{{config('app.url')}}/category/tour">ツアー紹介</a></span>
                 <span class="menu"><a href="{{config('app.url')}}/agreement">旅行業約款</a></span>
             </div>
         </div>
     </div>
     <div class="footer-bottom">
         <p class="copyright">Copyright © 長野電鉄株式会社 All Rights Reserved.</p>
     </div>
 </footer>
         
   

    <script src="{{asset('libs/slick/slick.min.js')}}"></script>
    <script src="{{asset('assets/js/theme.js')}}"></script>

    <script src='https://maps.google.com/maps/api/js?key=AIzaSyCG9SfPt8adGSdlgWkq8jdbt64mYaPRkaM' type="text/javascript"></script>
<script>
// マップ1設定
if (document.getElementById('lat') && document.getElementById('lng')) {
    var lat = document.getElementById('lat'),
        lng = document.getElementById('lng'),
        latlng = new google.maps.LatLng(lat.value, lng.value);
    
    var opt = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scaleControl: true
    };
    
    var map = new google.maps.Map( document.getElementById('map-canvas'), opt);
    map.setCenter( latlng );
    
    var marker = new google.maps.Marker({
        position: latlng,
        map: map
    });
}


// スライダー設定
$(function() {
    $('.thumb-item').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.thumb-item-nav'
    });
    $('.thumb-item-nav').slick({
        accessibility: true,
        autoplay: true,
        autoplaySpeed: 4000,
        speed: 400,
        arrows: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.thumb-item',
        focusOnSelect: true,
    });
});

// 日付選択時
$(function() {
	$('.selected-date').click(function () {
        var price = $(this).data('price');
		// 事前リセット
		$('.meeting-time').text('');
		$('.activity-time').text('');
		$('.price2').text('');

		// 日付代入
		var date = $(this).next('input').val(),
		    date = new Date(date),
		    year = date.getFullYear(),
            month = date.getMonth() + 1,
            day = date.getDate(),
            dayOfWeekStr = [ "日", "月", "火", "水", "木", "金", "土" ][date.getDay()];
		$('.res-date').text(year + '年' + month + '月' + day + '日（' + dayOfWeekStr + '）');
        $('input[name="res-date"]').val(year + '-' + month + '-' + day);
		// 集合時間代入、体験時間代入
		var plans = '<?= $plan_json ?>';
		    plans = JSON.parse(plans),
		    startHour ='00',
		    startMinute = '00',
		    endHour = '00',
		    endMinute ='00';

            //  startHour = plans[0].activities[0].start_hour,
            // startMinute = plans[0].activities[0].start_minute,
            // endHour = plans[0].activities[0].end_hour,
            // endMinute = plans[0].activities[0].end_minute;
        $.each(plans[0].activities, function(i, value) {
            $('.meeting-time').append('・' + value.start_hour + ':' +  value.start_minute + '<br />');
            if (value.is_overday && value.days_after != 00) {
                $('.activity-time').append('・' + value.start_hour + ':' +  value.start_minute + ' 〜 (' + value.days_after.substr(1, 2) + '日後の)' + value.end_hour + ':' + value.end_minute + '<br />');
            } else {
                $('.activity-time').append('・' + value.start_hour + ':' +  value.start_minute + ' 〜 ' + value.end_hour + ':' + value.end_minute + '<br />');
            }
        })
		// 料金代入
		var prices = [];
		$.each(plans[0].prices, function(i, value) {
		    if (value.week_flag == 1) {
		        var week_prices = [];
                        week_prices.push(value.monday);
                        week_prices.push(value.tuesday);
                        week_prices.push(value.wednesday);
                        week_prices.push(value.thursday);
                        week_prices.push(value.friday);
                        week_prices.push(value.saturday);
                        week_prices.push(value.sunday);
		        var minWeekVal = Math.min.apply(null, week_prices);
                        prices.push(minWeekVal);
		    } else {
                        prices.push(value.price);
		    }
		})
		var minVal = Math.min.apply(null, prices);
        //$('.price2').append(minVal.toLocaleString() + '円〜');
        $('.price2').append($(this).data('price').toLocaleString().replace(/----/g,'円<br>') + '');
		// 予約ボックス表示＆アンカー移動
		$('.reserve-arrow, .reserve-box').show();
		$('html,body').animate({
            scrollTop : $('.reserve-box').offset().top
        }, 'fast');

        return false;
    });
});

var price_type_id = $('#submit_select2').val() ;

if(price_type_id){
    $('.selected-date:not(.'+price_type_id+')').each(function(){
        $(this).css("display", "none");
    });
}

$("#submit_select2").change(function(){
    let price_type_id = $(this).val();
    $('.selected-date.'+price_type_id).each(function(){
        $(this).css("display", "block");
    });
    
    $('.selected-date:not(.'+price_type_id+')').each(function(){
        $(this).css("display", "none");
    });
    
});

// 予約ボタンクリック時

</script>

</body>
</html>


