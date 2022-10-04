<?php

    $url = "http://153.127.31.62/zenryo/public/api/kinds/json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $array = curl_exec($ch);
    $kinds = json_decode($array,true);

?>

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

    <!-- javascript -->
    <script src="https://zenryo.zenryo-ec.info/libs/jquery/jquery-3.4.1.min.js"></script>
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
                    <li><a href="/plan/list.php">ツアー紹介</a></li>
                    <li><a href="/company">会社概要</a></li>
                    <li><a href="/contact">お問い合わせ</a></li>
                </ul>
            </div>
        </div>
    </header>
    
    <main class="page-wrapper">
        <section class="section section--list section--with-top-margin">
            <div class="inner">
                <div class="section__content">
                    <div class="listContent">
                        <div class="listSide">
                            <p class="listSideHd">カテゴリ</p>

                        
                            <ul class="listSideUl">
                                <li><a href="https://zenryo.zenryo-ec.info/list.php?kind=all">すべて</a></li>
                                <?php
                                    foreach($kinds as $kind){
                                      echo '<li><a href="https://zenryo.zenryo-ec.info/list.php?kind='. $kind["number"] .'">'.$kind["name"] .'</a></li>';
                                    }
                                ?>
                            </ul>
                        </div>

                     
                        <div class="listMain">

                        <?php
                     $actual_link = "$_SERVER[REQUEST_URI]";

                     $params = '';
                     if(!isset($_GET['kind'])){
                        $params = 'all';
                     }
                     else{
                        $params = $_GET['kind'];
                     }


                     $url = "http://153.127.31.62/zenryo/public/api/plans/json/" . $params;
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_POST, false);
                     curl_setopt($ch, CURLOPT_URL, $url);
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                     $json = curl_exec($ch);
                     $plans = json_decode($json,true);


                    $j = 0;

                    

                    if(count($plans) > 0){
                        for ($i = 0 ; $i < count($plans) ; $i++) {
                            $plan = $plans[$i];
                            

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
                            
                                
                            ?>
    
    
                                <div class="listItem">
                                    <p class="listItemHd"><?=htmlspecialchars($plan["name"])?></p>
                                    <div class="listItemCont">
                                        <p class="listItemTxt"><?=nl2br(htmlspecialchars($plan["catchphrase"]))?></p>
                                        <div class="listItemInfo">
                                            <div class="leftP"><img src="https://zenryo.zenryo-ec.info/uploads/<?=$plan["file_path1"] ?>" alt=""></div>
                                            <div class="rightP">
                                                <div class="messageP">
                                                    <dl>
                                                        <dt>目的地</dt>
                                                        <dd><?=htmlspecialchars($plan["destination"])?>
                                                    </dd>
                                                    </dl>
                                                    <dl>
                                                        <dt>旅行日程</dt>
                                                        <dd><?=htmlspecialchars($plan["schedule"])?></dd>
                                                    </dl>
                                                    <dl>
                                                        <dt>出発日</dt>
                                                        <dd>
                                                        <?php if($plan["start_day"] == $plan["end_day"])
                                                            { 
                                                                echo htmlspecialchars($plan["start_day"]);
                                                            }
                                                            else{
                                                                echo htmlspecialchars($plan["start_day"])?>～<?=htmlspecialchars($plan["end_day"]);
                                                            }?>
                                                        </dd>
                                                    </dl>
                                                    <dl>
                                                        <dt>お食事</dt>
                                                        <dd><?=htmlspecialchars($plan["eat"])?></dd>
                                                    </dl>
                                                    <dl>
                                                        <dt>添乗員</dt>
                                                        <dd><?php if($plan["conductor_selected"]){echo '有';}else{echo '無';} ?></dd>
                                                    </dl>
                                                    <dl>
                                                        <dt>受付締切日時</dt>
                                                        <?php if($plan["res_type"] == 0){ ?>
                                                            <dd>出発日の<?=htmlspecialchars($plan["res_end_day"])?><?php if($plan["res_end_type"] == 0){echo '日前';}else{echo '週間前' ;} ?></dd>
                                                        <?php }
                                                        else{?>
                                                            <dd>出発日の<?=htmlspecialchars($plan["req_before_day"])?><?php if($plan["req_before_type"] == 0){echo '日前';}else{echo '週間前' ;} ?></dd>
                                                        <?php }?>
                                                    </dl>
                                                </div>
                                                <p class="priceP">旅行代金（お一人様<span>¥<?php echo number_format($min_value)?>〜¥<?php echo number_format($max_value)?></span></p>
                                                <?php
                                                    date_default_timezone_set('Asia/Tokyo');

                                                    $date = new DateTimeImmutable(date('Y-m-d'));
                                                    $end =  new DateTimeImmutable($plan['end_day']);
                                                    $time = date('H');
                                                    $interval = date_diff($end, $date);

                                                    $compare = intval($interval->format('%R%a'));

                                                    if( $compare > 0 ){
                                                       echo '<p class="btnP btnLink01" style="background:#777">プラン終了</p>';
                                                    }
                                                    else if($compare == 0){
                                                        if($plan["res_type"] == 0){
                                                            if(intval($time) > intval($plan['res_end_time'])){
                                                                echo '<p class="btnP btnLink01" style="background:#777">プラン終了</p>';
                                                            }
                                                            else{
                                                                echo '<p class="btnP"><a href="https://zenryo.zenryo-ec.info/detail.php?plan_id='. htmlspecialchars($plan["id"]) .'" class="btnLink01">プラン詳細をみる</a></p>';
                                                            }
                                                        }
                                                        else{
                                                            if(intval($time) > intval($plan['req_before_time'])){
                                                                echo '<p class="btnP btnLink01" style="background:#777">プラン終了</p>';
                                                            }
                                                            else{
                                                                echo '<p class="btnP"><a href="https://zenryo.zenryo-ec.info/detail.php?plan_id='. htmlspecialchars($plan["id"]) .'" class="btnLink01">プラン詳細をみる</a></p>';
                                                            }
                                                        }
                                                      
                                                    }
                                                    else{
                                                        echo '<p class="btnP"><a href="https://zenryo.zenryo-ec.info/detail.php?plan_id='. htmlspecialchars($plan["id"]) .'" class="btnLink01">プラン詳細をみる</a></p>';
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php      
                            }
                            
                        }
                        
                    else{
                        ?>
                        <p class="emptyCategory">coming soon</p>
                        <?php
                    }?>
                   

                   
               
                            </div>
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
                    <span class="menu"><a href="/plan/list.php">ツアー紹介</a></span>
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
