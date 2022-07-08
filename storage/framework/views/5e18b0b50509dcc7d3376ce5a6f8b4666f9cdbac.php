<?php $__env->startSection('title', '株式会社全旅 - 予約完了'); ?>
<?php $__env->startSection('content'); ?>
    <div class="px-3 py-3 pt-md-5 mx-auto text-center">
        <ul class="list-unstyled">
            <li></li>
        </ul>
    </div>
    <h5 class="mb-3 p-2 rounded bg-primary text-light">ご予約完了</h5>
    <!-- <hr> -->
    <?php if( isset($message) &&  $message != null): ?>
        <p class="alert alert-danger"><?php echo e($message); ?></p>
    <?php endif; ?>
    <table class="table table-striped" style="margin: 40px 0;">
        <tbody>
        <tr>
            <td>予約確定メールを送信しました。決済エラー時やキャンセルなどのお問い合わせは直接実施会社へご連絡ください。</td>
        </tr>
        </tbody>
    </table>

    <div class="row">

        <div class="col-md-12">
            <!-- <hr class="mb-4"> -->
            <a class="btn btn-primary btn" href="../list.php">TOPに戻る</a>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.parents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/user/reservations/result.blade.php ENDPATH**/ ?>