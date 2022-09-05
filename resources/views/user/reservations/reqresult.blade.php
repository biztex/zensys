
<!-- HTMLコード -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/img/favicon2_2.ico" />

    <!-- css -->
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/libs/slick/slick.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/libs/slick/slick-theme.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/assets/css/theme.css">
    <link rel="stylesheet" href="https://zenryo.zenryo-ec.info/assets/css/add.css">

    <!-- javascript -->
    <script src="https://zenryo.zenryo-ec.info/libs/jquery/jquery-3.4.1.min.js"></script>
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
                        <li>予約内容確認</li>
                        <li class="is_active">@if($req_result == 0)予約完了@else リクエスト受付完了 @endif</li>
                    </ul>
                    <div class="reserveBox">
                        <h3 class="reserveBoxHd">@if($req_result == 0)予約完了@else リクエスト受付完了 @endif </h3>
                        <div class="reserveCont thanksCont">
                            <p class="thanksHd">@if($req_result == 0)予約@else リクエスト受付 @endif が完了しました。</p>
                            <p class="thanksTxt">@if($req_result == 0)予約完了@else リクエスト受付完了 @endifメールをお送りしましたので、ご確認ください。</p>
                            <ul class="reserveButton">
                                <li><a href="index.html" class="btnLink01">トップへ戻る</a></li>
                            </ul>
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

    <script src="https://zenryo.zenryo-ec.info/libs/slick/slick.min.js"></script>
    <script src="https://zenryo.zenryo-ec.info/assets/js/theme.js"></script>
</body>
</html>