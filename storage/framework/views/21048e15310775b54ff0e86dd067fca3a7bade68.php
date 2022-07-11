

<?php $__env->startSection('title', '基本情報'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<p class="pt-3">
基本情報
</p>
<script src="https://ajaxzip3.github.io/ajaxzip3.js"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">変更したい項目を編集後、「変更する」ボタンを押してください</div>
                <div class="card-body">
                    
                    <?php if(Session::has('message')): ?>
                    <div class="alert alert-primary">
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
                    <form action="<?php echo e(config('app.url')); ?>client/companies/update/<?php echo e($companies->id); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="form-group row">
                            <label for="id" class="col-md-2 col-form-label text-md-right"><?php echo e(__('ID')); ?></label>
                            <div class="col-md-6">
                                <input id="id" class="input-group-text text-md-left" type="text" name="id" value="<?php echo e(old('$companies->id', $companies->id)); ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('会社名')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo e(old('name',$companies->name)); ?>">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('旅行業登録番号')); ?></label>
                            <div class="col-md-6">
                                <input id="company_number" type="text" class="form-control" name="company_number" value="<?php echo e(old('company_number',$companies->company_number)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('ロゴ画像')); ?></label>
                            <div id="div-img1" class="col-md-4">
                              <?php if(empty($companies->file_path1)): ?>
                              <input type="file" name="file_path1">
                              <?php else: ?>
                              <img id="img1" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($companies->file_path1); ?>" width="auto" height="150px">
                              <input id="hidden1" type="hidden" name="old_file_path1" value="<?php echo e(old('file_path1',$companies->file_path1)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button1" class="col-md-4 file_path1">
                              <?php if(empty($companies->file_path1)): ?>
                              <input type='button' id="clearbtn1" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(1)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn1" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(1)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('エリア')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="area" value="<?php echo e(old('area',$companies->area)); ?>" placeholder="札幌／ススキノ・大通">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('住所')); ?></label>

                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('郵便番号')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="postal_code" value="<?php echo e(old('postal_code',$companies->postal_code)); ?>" 
                                 onKeyUp="AjaxZip3.zip2addr(this,'','prefecture','address');">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('都道府県')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="prefecture" value="<?php echo e(old('prefecture',$companies->prefecture)); ?>">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('住所')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="address" value="<?php echo e(old('address',$companies->address)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('マンション・ビル名')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="buildings" value="<?php echo e(old('buildings',$companies->buildings)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('問合せ先TEL')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="tel_inquiry" value="<?php echo e(old('tel_inquiry',$companies->tel_inquiry)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('問合せ先URL')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="url" value="<?php echo e(old('url',$companies->url)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('個人情報取扱URL')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="url2" value="<?php echo e(old('url',$companies->url2)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">問合せ先備考</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="memo" rows="5"><?php echo e(old('memo',$companies->memo)); ?></textarea>
                            </div>
                        </div>
<!--
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('ロゴ画像')); ?></label>
                            <div id="div-img1" class="col-md-4">
                              <?php if(empty($companies->file_path1)): ?>
                              <input type="file" name="file_path1">
                              <?php else: ?>
                              <img id="img1" src="/public/uploads/<?php echo e($companies->file_path1); ?>" width="auto" height="150px">
                              <input id="hidden1" type="hidden" name="old_file_path1" value="<?php echo e(old('file_path1',$companies->file_path1)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button1" class="col-md-4 file_path1">
                              <?php if(empty($companies->file_path1)): ?>
                              <input type='button' id="clearbtn1" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(1)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn1" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(1)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('ロゴ画像')); ?><br /><small>（ダークモード用）</small></label>
                            <div id="div-img2" class="col-md-4">
                              <?php if(empty($companies->file_path2)): ?>
                              <input type="file" name="file_path2">
                              <?php else: ?>
                              <img id="img2" src="/public/uploads/<?php echo e($companies->file_path2); ?>" width="auto" height="150px">
                              <input id="hidden2" type="hidden" name="old_file_path2" value="<?php echo e(old('file_path2',$companies->file_path2)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button2" class="col-md-4 file_path2">
                              <?php if(empty($companies->file_path2)): ?>
                              <input type='button' id="clearbtn2" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(2)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn2" class="btn btn-danger btn-sm" value='画像2を削除' onClick='deleteFile(2)'/>
                              <?php endif; ?>
                            </div>
                        </div>
-->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" name='action' value='edit'>
                                    <?php echo e(__('変更する')); ?>

                                </button>
<!--
                                <a href="/client/companies" class="btn btn-secondary">戻る</a>
-->
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
<script>
function clearFile(i) {
    $('input[name="file_path' + i + '"]').val(null);
}
function deleteFile(i) {
    $('#img' + i).remove();
    $('#deletebtn' + i).remove();
    $('#hidden' + i).remove();
    $('#div-img' + i).append('<input type="file" name="file_path' + i + '">');    
    $('#div-button' + i).append('<input type="button" id="clearbtn' + i + '" class="btn btn-light btn-sm" value="画像選択を解除" onClick="clearFile(' + i + ')"/>');    
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Task\crowd\_08_laravel\zensys\resources\views/client/companies/edit.blade.php ENDPATH**/ ?>