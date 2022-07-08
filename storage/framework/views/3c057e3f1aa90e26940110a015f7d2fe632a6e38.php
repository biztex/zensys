<?php $__env->startSection('title', '管理者追加'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-2">
  <div class="col-sm-6 mt-3">
    <p>管理者追加</p>
  </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">項目を入力後、「追加する」ボタンを押してください</div>
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
                    <form action="<?php echo e(config('app.url')); ?>client/store" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('POST'); ?>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('名前')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('メールアドレス')); ?></label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="<?php echo e(old('email')); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-2 col-form-label text-md-right"><?php echo e(__('パスワード')); ?></label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-2 col-form-label text-md-right"><?php echo e(__('パスワード確認')); ?></label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-info" name='action' value='edit'>
                                    <?php echo e(__('追加する')); ?>

                                </button>
                                <a href="<?php echo e(config('app.url')); ?>client" class="btn btn-secondary">戻る</a>
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/client/create.blade.php ENDPATH**/ ?>