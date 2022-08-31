<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{config('app.url')}}css/template.css">
</head>
@php
    $url =  "http://153.127.31.62/zenryo/public/api/company/json";
    $company_array = file_get_contents($url);
    $companies = json_decode($company_array, true);
    $company = $companies[0];
@endphp

<body class="bg-light">

    <div class="header">
        <img src="{{config('app.url')}}uploads/{{ $company['file_path1'] }}" alt="" width="">
    </div>
    <!-- <hr> -->
    <div class="inner">

    @yield('content')
    </div>
    
    <footer class="footer_wrap">
        <!-- <a href="company.php">会社概要</a> -->
        <!-- <a href="tradelaw.php">特定商取引法に基づく表記</a> -->
        <!-- <a href="privacy.php">プライバシーポリシー</a> -->
    </footer>
    <!-- <div class="copy">Copyright © ZENRYO Co.,Ltd All Rights Reserved.</div> -->

<script src=" {{ mix('js/app.js') }} "></script>
<script src=" {{ asset('js/menu.js') }} "></script>
</body>
</html>
