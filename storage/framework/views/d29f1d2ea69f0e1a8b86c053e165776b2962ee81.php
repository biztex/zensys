<?php echo e($name_last); ?> <?php echo e($name_first); ?>　様

この度は株式会社全旅からのご予約、誠にありがとうございました。

以下の内容にて予約を承っておりますのでご確認ください。

※キャンセルをご希望の場合は直接実施会社へご連絡ください

【予約情報】
=====================================
予約番号　：　<?php echo e($number); ?>

予約状況　：　予約確定
プラン名　：　<?php echo e($plan); ?>　　
お名前　　：　<?php echo e($name_last); ?> <?php echo e($name_first); ?>　様(<?php echo e($kana_last); ?> <?php echo e($kana_first); ?>)
メールアドレス ：　<?php echo e($email); ?>

住所 ：　<?php echo e($postalcode); ?>　<?php echo e($prefecture); ?><?php echo e($address); ?>

電話番号　　：　<?php echo e($tel); ?>

生年月日　　：　<?php echo e($birth_year); ?>/<?php echo e($birth_month); ?>/<?php echo e($birth_day); ?>

当日緊急連絡先　　：　<?php echo e($tel2); ?>

予約日　　：　<?php echo e($date); ?>

予約時間　：　<?php echo e($activity); ?>

決済方法　：　<?php echo e($payment); ?> （現地にてご案内いたします）
予約人数
----------------------------------------------------
<?php
  foreach($reservation->plan->prices as $i => $price) {
      if ($price->week_flag == 0) {
        echo $price->price_types->name . " / " . number_format($price->price) . " 円 × " . $reservation->{'type'.$price->price_types->number.'_number'} . ' = ' . number_format($price->price * $reservation->{'type'.$price->price_types->number.'_number'}) . ' 円' . "\n";
      }
      if ($price->week_flag == 1) {
        echo $price->price_types->name . " / " . number_format($price->{$weekday}) . " 円 × " . $reservation->{'type'.$price->price_types->number.'_number'} . ' = ' . number_format($price->{$weekday} * $reservation->{'type'.$price->price_types->number.'_number'}) . ' 円' . "\n";
      }
  }
?>

決済金額合計：<?php echo e(number_format($amount)); ?>円
----------------------------------------------------
=====================================

=====================================
株式会社全旅

所在地	〒104-0061　東京都中央区銀座8-13-1　銀座三井ビルディング2F
電話番号	03-6264-3132
公式サイト:　 https://www.zenryo.co.jp
=====================================
<?php /**PATH /var/www/html/zenryo/resources/views/user/reservations/spotemail.blade.php ENDPATH**/ ?>