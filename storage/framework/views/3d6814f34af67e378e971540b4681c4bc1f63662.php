<?php $__env->startSection('title', '予約編集'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-2">
  <div class="col-sm-6 mt-3">
    <p>予約編集</p>
  </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">変更したい項目を編集後、「変更する」ボタンを押してください</div>
                <div class="card-body">
                    
                    <?php if(Session::has('message')): ?>
                    <div class="alert alert-info">
                        <?php echo e(session('message')); ?>

                    </div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    </div>
                    <?php endif; ?>
                    <form action="<?php echo e(config('app.url')); ?>client/reservations/update/<?php echo e($reservations->id); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="form-group row">
                            <label for="id" class="col-md-2 col-form-label text-md-right"><?php echo e(__('ID')); ?></label>
                            <div class="col-md-6">
                                <input id="id" class="input-group-text text-md-left" type="text" name="id" value="<?php echo e(old('$reservations->id', $reservations->id)); ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('プラン名')); ?></label>
                            <div class="col-md-6">
                                <a target="_blank" class="font-weight-bold" style="line-height: 2.4;" href="<?php echo e(config('app.url')); ?>client/plans/edit/<?php echo e($reservations->plan->id); ?>"><?php echo e($reservations->plan->name); ?> <small>（別ページで開く）</small></i></a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約番号')); ?></label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="<?php echo e($reservations->order_id); ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('受付タイプ')); ?></label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="<?php if($reservations->plan->res_type == '0'): ?> 即時 <?php elseif($reservations->plan->res_type == '1'): ?> 即時・リクエスト併用 <?php else: ?> リクエスト予約 <?php endif; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約ステータス')); ?></label>
                            <div class="col-md-3">
                                <select class="form-control" name="status">
                                  <option disabled selected>選択してください</option>
                                  <option value="予約確定" <?php if(old('status',$reservations->status)=='予約確定'): ?> selected  <?php endif; ?>>予約確定</option>
                                  <option value="リクエスト予約" <?php if(old('status',$reservations->status)=='リクエスト予約'): ?> selected  <?php endif; ?>>リクエスト予約</option>
                                  <option value="未決済" <?php if(old('status',$reservations->status)=='未決済'): ?> selected  <?php endif; ?>>未決済</option>
                                  <option value="キャンセル" <?php if(old('status',$reservations->status)=='キャンセル'): ?> selected  <?php endif; ?>>キャンセル</option>
                                </select>
                            </div>
                            <div class="col-md-4">
			        <small class="text-red">※クレジットカード決済の場合、決済日から60日を超えるとDGFT側の決済をキャンセルすることはできません</small>
                            </div>
                        </div>
                        <?php $__currentLoopData = $reservations->plan->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">体験日時(<?php echo e($loop->index + 1); ?>)</label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="<?php echo e($reservations->activity_date); ?>　 <?php echo e($activity->start_hour); ?>:<?php echo e($activity->start_minute); ?> 〜 <?php echo e($activity->end_hour); ?>:<?php echo e($activity->end_minute); ?>" disabled>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約受付日時')); ?></label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="<?php echo e(substr($reservations->created_at,0, 16)); ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約確定日時')); ?></label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="<?php echo e(substr($reservations->fixed_datetime,0, 16)); ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約者')); ?></label>
                            <div class="col-md-6">
                                <a target="_blank" class="font-weight-bold" style="line-height: 2.4;" href="<?php echo e(config('app.url')); ?>client/users/edit/<?php echo e($reservations->user->id); ?>"><?php echo e($reservations->user->name_last); ?> <?php echo e($reservations->user->name_first); ?> <small>（別ページで開く）</small></i></a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約者への質問')); ?></label>
                            <div class="col-md-6">
                                <textarea id="name" type="text" class="form-control" name="" rows="5" disabled><?php echo e($reservations->plan->question_content); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('予約者からの回答')); ?></label>
                            <div class="col-md-6">
                                <textarea id="name" type="text" class="form-control" name="" rows="5" disabled><?php echo e($reservations->answer); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('支払方法')); ?></label>
                            <div class="col-md-3">
			        <select name="payment_method" class="form-control">
				    <option value="" selected>選択してください</option>
				    <option value="0" <?php if(old('payment_method', $reservations->payment_method)=='0'): ?> selected <?php endif; ?>>現地払い</option>
				    <option value="1" <?php if(old('payment_method', $reservations->payment_method)=='1'): ?> selected <?php endif; ?>>事前払い</option>
				    <option value="2" <?php if(old('payment_method', $reservations->payment_method)=='2'): ?> selected <?php endif; ?>>事前コンビニ決済</option>
				    <option value="3" <?php if(old('payment_method', $reservations->payment_method)=='3'): ?> selected <?php endif; ?>>事前クレジットカード決済</option>
				</select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><?php echo e(__('その他 備考・特記事項')); ?></label>
                            <div class="col-md-6">
                                <textarea id="name" type="text" class="form-control" name="memo" rows="5" placeholder="※登録された内容は、予約情報として保存されます。"><?php echo e($reservations->memo); ?></textarea>
                            </div>
                        </div>
                    <div class="form-group row mt-3 bg-dark">
                        <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-th-list"></i> 料金情報</span></label>
                    </div>
                    <div class="form-group row mb-2 float-right">
                        <div class="col-md-2">
		    	<input type="button" class="update-price btn btn-sm btn-secondary" value="価格表更新">
                        </div>
                    </div>
                <table class="table table-bordered">
                  <thead class="bg-light">
                    <tr>
                      <th style="width: 40%; text-align: center;">料金区分</th>
                      <th style="width: 15%; text-align: center;">単価</th>
                      <th style="width: 15%; text-align: center;">人数</th>
                      <th style="width: 30%; text-align: center;">金額</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $__currentLoopData = $reservations->plan->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
<td>
<?php echo e($price->price_types->name); ?>

</td>

<?php if($price->week_flag == 0): ?>
<td style="text-align: right;"><?php echo e(number_format($price->price)); ?> 円</td>
<input type="hidden" id="price<?php echo e($loop->index + 1); ?>" value="<?php echo e($price->price); ?>">
<?php else: ?>
<td style="text-align: right;"><?php echo e(number_format($price->{$weekday})); ?> 円</td>
<input type="hidden" id="price<?php echo e($loop->index + 1); ?>" value="<?php echo e($price->{$weekday}); ?>">
<?php endif; ?>
<td style="text-align: right; padding-left: 50px;"><div class="row"><input id="per-number<?php echo e(($loop->index + 1)); ?>" class="number-input" name="type<?php echo e($price->type); ?>_number" value="<?php $i = $price->type;echo $reservations->{'type' . $i . '_number'};?>"> <span style="line-height: 1.8;" class="col-md-1"> 名</span></div></td>
<input type="hidden" class="col-md-6 text-right" value="<?php $i = $price->type;echo $reservations->{'type' . $i . '_number'};?>">

<td id="per-text-price<?php echo e(($loop->index + 1)); ?>" style="text-align: right;">
<?php
$i = $price->type;
$type_number = $reservations->{'type' . $i . '_number'};
if ($price->week_flag == 0) {
    $result = ($type_number * $price->price);
} else {
    $result = $type_number * $price->{$weekday};
}
echo $result . ' 円';
?>
</td>
<input type="hidden" id="per-price<?php echo e(($loop->index + 1)); ?>" value="<?php echo e($result); ?>">
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr>
                      <td colspan="2" class="bg-light font-weight-bold">人数・金額合計</td>
                      <td id="total-number" style="text-align: center;" class="font-weight-bold"></td>
                      <td id="total-price" style="text-align: right;" class="font-weight-bold"></td>
                    </tr>
                  </tbody>
                </table>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
				<input type="submit" class="submit btn btn-primary" data-action="<?php echo e(config('app.url')); ?>client/reservations/update/<?php echo e($reservations->id); ?>" value="変更する">
				<input type="submit" class="submit-send btn btn-danger" data-action="<?php echo e(config('app.url')); ?>client/reservations/sendpaymentmail/<?php echo e($reservations->id); ?>" value="決済メール送信">
                                <a href="<?php echo e(config('app.url')); ?>client/reservations/" class="btn btn-secondary">戻る</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/default.js')); ?>"></script>
<script>
$(document).ready(function(){
    var totalNumber = 0,
        totalPrice = 0;
    for (var i = 1 ; i <= 6 ; i++) {
        if ($('#per-number' + i).val()) {
            var tmpPerNumber = $.trim($('#per-number' + i).val()),
                perNumber = parseInt(tmpPerNumber); 
                totalNumber += perNumber;
        }
        if ($('#per-price' + i).val()) {
            var tmpPerPrice = $.trim($('#per-price' + i).val()),
                perPrice = parseInt(tmpPerPrice); 
                totalPrice += perPrice;
        }
    }
    $('#total-number').text(totalNumber + ' 名');
    $('#total-price').text(totalPrice.toLocaleString() + ' 円');
});
$('.update-price').click(function() {
    var totalNumber = 0,
        totalPrice = 0;
    for (var i = 1 ; i <= 6 ; i++) {
        if ($('#per-number' + i).val()) {
            var tmpPerNumber = $.trim($('#per-number' + i).val()),
                perNumber = parseInt(tmpPerNumber); 
            totalNumber += perNumber;
            var tmpPrice = $.trim($('#price' + i).val()),
                price = parseInt(tmpPrice); 
            price = perNumber * price;
            $('#per-text-price' + i).text(price.toLocaleString() + " 円");
                totalPrice += price;
        }
    }
    $('#total-number').text(totalNumber + ' 名');
    $('#total-price').text(totalPrice.toLocaleString() + ' 円');
});

// 送信ボタン切り分け
$('.submit').click(function() {
  $(this).parents('form').attr('action', $(this).data('action'));
  $(this).parents('form').submit();
});
$('.submit-send').click(function() {
  var checked = confirm("【確認】送信してよろしいですか？");
  if (checked == true) {
      $(this).parents('form').attr('action', $(this).data('action'));
      $(this).parents('form').submit();
      return true;
  } else {
      return false;
  }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/client/reservations/edit.blade.php ENDPATH**/ ?>