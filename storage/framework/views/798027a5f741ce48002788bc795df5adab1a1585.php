<?php $__env->startSection('title', '管理画面'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1 class="m-0 text-dark">ダッシュボード</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">ログインしました</p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/client/mypage.blade.php ENDPATH**/ ?>