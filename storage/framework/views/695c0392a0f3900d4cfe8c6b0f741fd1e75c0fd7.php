<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo e(asset('css/menu.css')); ?>" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="<?php echo e(config('app.url')); ?>css/template.css">
</head>
<body class="bg-light">

    <div class="header">
        <img src="<?php echo e(asset('img/logo.png')); ?>" alt="" width="">
    </div>
    <!-- <hr> -->
    <div class="inner">

    <?php echo $__env->yieldContent('content'); ?>
    </div>
    
    <footer class="footer_wrap">
        <!-- <a href="company.php">会社概要</a> -->
        <!-- <a href="tradelaw.php">特定商取引法に基づく表記</a> -->
        <!-- <a href="privacy.php">プライバシーポリシー</a> -->
    </footer>
    <div class="copy">Copyright © ZENRYO Co.,Ltd All Rights Reserved.</div>

<script src=" <?php echo e(mix('js/app.js')); ?> "></script>
<script src=" <?php echo e(asset('js/menu.js')); ?> "></script>
</body>
</html>
<?php /**PATH /var/www/html/zenryo/resources/views/layouts/parents.blade.php ENDPATH**/ ?>