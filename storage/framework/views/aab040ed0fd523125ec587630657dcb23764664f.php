

<?php $__env->startSection('title', 'プラン編集'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script> -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="<?php echo e(asset('/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<p>
プラン編集
</p>

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
                    <form action="<?php echo e(config('app.url')); ?>client/plans/update/<?php echo e($plans->id); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="form-group row mt-3 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 基本設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-md-2 col-form-label text-md-right"><?php echo e(__('ID')); ?></label>
                            <div class="col-md-6">
                                <input id="id" class="input-group-text text-md-left" type="text" name="id" value="<?php echo e(old('$plans->id', $plans->id)); ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('ツアーコード')); ?></label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code" value="<?php echo e(old('code',$plans->code)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> サイトカテゴリ</label>
                            <div class="col-md-6">
                                <select class="form-control" name="kind">
                                <?php $__currentLoopData = $kinds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($kind->number); ?>" <?php if(old('kind',$plans->kind)==$kind->number): ?> selected  <?php endif; ?>><?php echo e($kind->name); ?></option>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('ステータス')); ?></label>
                            <div class="col-md-6">
                                <select class="form-control" name="is_listed">
                                  <option disabled selected>選択してください</option>
                                  <option value="1" <?php if(old('is_listed',$plans->is_listed)==1): ?> selected  <?php endif; ?>>掲載中</option>
                                  <option value="0" <?php if(old('is_listed',$plans->is_listed)==0): ?> selected  <?php endif; ?>>休止</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('プラン名')); ?></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo e(old('name',$plans->name)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> キャッチコピー</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="catchphrase" rows="5" placeholder="※最大100文字まで"><?php echo e(old('catchphrase',$plans->catchphrase)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> プラン説明文</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="description" rows="15" placeholder="※最大1200文字まで"><?php echo e(old('description',$plans->description)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 出発日</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="start_day" value="<?php echo e(old('start_day',$plans->start_day)); ?>">
                            </div>
                            <label class="col-md-1 col-form-label text-md-center">〜</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="end_day" value="<?php echo e(old('end_day',$plans->end_day)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">繰り返し設定</label>
                            <div class="col-md-2">
                                <select class="form-control" name="repetition_flag">
                                  <option value="0" <?php if(old('repetition_flag',$plans->repetition_flag)==0): ?> selected  <?php endif; ?>>曜日</option>
                                  <option value="1" <?php if(old('repetition_flag',$plans->repetition_flag)==1): ?> selected  <?php endif; ?>>日にち</option>
                                </select>
                            </div>
                            <p class="col-md-4 col-form-label text-md-left">※販売期間内で有効となります</p>
                        </div>
                        <div class="form-group row ml-5 week-flag">
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="monday" value="<?php if($plans->monday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->monday == 1): ?> checked <?php endif; ?>> <span> 月</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="tuesday" value="<?php if($plans->tuesday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->tuesday == 1): ?> checked <?php endif; ?>> <span> 火</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="wednesday" value="<?php if($plans->wednesday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->wednesday == 1): ?> checked <?php endif; ?>> <span> 水</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="thursday" value="<?php if($plans->thursday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->thursday == 1): ?> checked <?php endif; ?>> <span> 木</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="friday" value="<?php if($plans->friday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->friday == 1): ?> checked <?php endif; ?>> <span> 金</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="saturday" value="<?php if($plans->saturday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->saturday == 1): ?> checked <?php endif; ?>> <span> 土</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="sunday" value="<?php if($plans->sunday == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->sunday == 1): ?> checked <?php endif; ?>> <span> 日</span>
                            </label>
                        </div>
                        <div class="form-group row ml-5 month-flag">
                            <label class="col-md-1 col-form-label text-md-right ml-5">毎月</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="repetition_day" value="<?php echo e(old('repetition_day',$plans->repetition_day)); ?>">
                            </div>
                            <label class="col-md-1 col-form-label text-md-right">日に設定</label>
                        </div>
                        <div class="form-group row week-flag">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">選択した曜日が<br />祝日の場合</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="holiday_selected" value="1" <?php if($plans->holiday_selected == 1): ?> checked <?php endif; ?>> <span> 開催する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="holiday_selected" value="0" <?php if($plans->holiday_selected == 0): ?> checked <?php endif; ?>> <span> 開催しない</span>
                            </label>
                        </div>
                        <div class="form-group row week-flag">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">選択していない曜日が<br />祝日の場合</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="holiday_nonselected" value="1" <?php if($plans->holiday_nonselected == 1): ?> checked <?php endif; ?>> <span> 開催する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="holiday_nonselected" value="0" <?php if($plans->holiday_nonselected == 0): ?> checked <?php endif; ?>> <span> 開催しない</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('目的地')); ?></label>
                            <div class="col-md-6">
                                <input id="destination" type="text" class="form-control" name="destination" value="<?php echo e(old('destination',$plans->destination)); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('お食事')); ?></label>
                            <div class="col-md-6">
                                <input id="eat" type="text" class="form-control" name="eat" value="<?php echo e(old('eat',$plans->eat)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('旅行日程')); ?></label>
                            <div class="col-md-6">
                                <input id="schedule" type="text" class="form-control" name="schedule" value="<?php echo e(old('schedule',$plans->schedule)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('最小催行人員')); ?></label>
                            <div class="col-md-6">
                                <input id="min_cnt" type="text" class="form-control" name="min_cnt" value="<?php echo e(old('min_cnt',$plans->min_cnt)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('添乗員の有無')); ?></label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="conductor_selected" value="1" <?php if($plans->conductor_selected == 1): ?> checked <?php endif; ?>> <span> あり</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="conductor_selected" value="0" <?php if($plans->conductor_selected == 0): ?> checked <?php endif; ?>> <span> なし</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('受付締切日時')); ?></label>
                            <div class="col-md-6">
                                <input id="deadline" type="text" class="form-control" name="deadline" value="<?php echo e(old('deadline',$plans->deadline)); ?>">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('旅行条件書')); ?></label>
                            <div class="col-md-6">
                                <div class="col-md-12 p-0">
                                    <input id="notice" type="text" name="notice" class="form-control" value="<?php echo e(old('notice',$plans->notice)); ?>">or
                                </div>
                                
                                <div class="col-md-12 mt-2">
                                    
                                    <?php if(empty($plans->file_path11)): ?>
                                        <input type="file" class="col-md-6" name="file_path11" id="file_path11">
                                    <?php else: ?>
                                        <a id="file_path11_link" href="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path11); ?>" ><?php echo e($plans->file_path11); ?></a>
                                        <input id="file_path11" type="hidden"  class="col-md-4" name="file_path11" value="<?php echo e(old('file_path6',$plans->file_path11)); ?>">
                                        <button type="button"class="btn btn-secondary p-1 delete_file" onclick="PdfDelete()">削除</button>
                                    <!-- <input type="checkbox" name="del_fiel11" value="1">削除 -->
                                    <?php endif; ?>
                                </div>
                              
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('利用宿泊施設')); ?></label>
                            <div class="col-md-6">
                                <textarea id="institution" type="textarea" class="form-control" name="institution" rows="5" placeholder="※最大100文字まで"><?php echo e(old('institution',$plans->institution)); ?></textarea>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('利用交通機関')); ?></label>
                            <div class="col-md-6">
                                <textarea id="transportation" type="textarea" class="form-control" name="transportation" rows="5" placeholder="※最大100文字まで"><?php echo e(old('transportation',$plans->transportation)); ?></textarea>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('乗車地　設定')); ?></label>
                            <div class="col-md-4">
                                <input id="boarding" type="text" class="form-control" name="boarding" value="<?php echo e(old('boarding',$plans->boarding)); ?>" placeholder="カンマ区切り複数">
                            </div>
                            <div class="col-md-2">
                                <select  class="form-control" name="boarding_type">
                                    <option value="1" <?php if($plans->boarding_type == 1): ?> selected <?php endif; ?>>必須
                                    <option value="2" <?php if($plans->boarding_type == 2): ?> selected <?php endif; ?>>必須ではない
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('降車地　設定')); ?></label>
                            <div class="col-md-4">
                                <input id="drop" type="text" class="form-control" name="drop" value="<?php echo e(old('drop',$plans->drop)); ?>" placeholder="カンマ区切り複数">
                            </div>
                            <div class="col-md-2">
                                <select  class="form-control" name="drop_type">
                                    <option value="1" <?php if($plans->drop_type == 1): ?> selected <?php endif; ?>>必須
                                    <option value="2" <?php if($plans->drop_type == 2): ?> selected <?php endif; ?>>必須ではない
                                </select>
                            </div>
                        </div>
   
       <!--                  <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">自動延長設定</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="is_autoextend" value="1" <?php if($plans->is_autoextend == 1): ?> checked <?php endif; ?>> <span> 自動で延長する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="is_autoextend" value="0" <?php if($plans->is_autoextend == 0): ?> checked <?php endif; ?>> <span> 自動で延長しない</span>
                            </label>
                        </div> -->
                       
             
<!--                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name="add-activity">
                                    <?php echo e(__('+ 体験時間を追加する')); ?>

                                    <input type="hidden" name="add-activity" value="1">
                                </div>
                            </div>
                        </div> -->
                                                <!-- <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> ジャンル</label>
                        </div>
                        <div class="form-group row ml-5">
                            <label class="col-md-2 col-form-label text-md-right">大カテゴリ</label>
                            <div class="col-md-5">
                                <select class="form-control" name="genre_category">
                                    <option value="0" selected>選択してください</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($loop->index + 1); ?>"><?php echo e($category->category); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row ml-5">
                            <label class="col-md-2 col-form-label text-md-right">小カテゴリ</label>
                            <div class="col-md-5">
                                <select class="form-control" name="genre_name">
                                    <option value="0" selected> - </option>
                                </select>
                            </div>
                            <p class="col-md-4 col-form-label text-md-left">※選択した大カテゴリから絞り込まれます</p>
           
                        </div> -->
                         <!-- <div class="after-road-section-1"></div> -->
                        <hr />
                                                 <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 行程表</span></label>
                        </div>
                          <?php $__currentLoopData = $plans-> road_maps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $road_map): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                         <div class="after-road-section-<?php echo e($loop->index + 1); ?>">

                         
                                                    <div class="form-group row">
                                <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 行程表 (<?php echo e($loop->index + 1); ?>)</label>
                                <?php if($loop->last && $loop->count != 1): ?>
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-road-<?php echo e($loop->index + 1); ?>' value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この行程表を削除
                                    </div>
                                </div>
                                <?php elseif(!$loop->first && $loop->count != 1): ?> 
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-road-<?php echo e($loop->index + 1); ?>' style="display: none;" value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この行程表を削除
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('タイトル')); ?></label>
                            <div class="col-md-6">
                                <input id="road_map_title" type="text" class="form-control" name="road_map_title<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('road_map_title',$road_map->road_map_title)); ?>">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('利用宿泊施設')); ?></label>
                            <div class="col-md-6">
                                <input id="road_map_build" type="text" class="form-control" name="road_map_build<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('road_map_build',$road_map->road_map_build)); ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('朝食')); ?></label>
                            <div class="col-md-6 d-flex align-items-center">
                            <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                            <input id="road_eat1_1" type="radio"  name="road_eat1_<?php echo e($loop->index + 1); ?>" value="1" <?php if($road_map->road_eat1): ?> checked <?php endif; ?>>
                                <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                             </label>
                             <label class="custom-control custom-radio pl-2  m-0 font-weight-normal" role="button">
                             <input id="road_eat1_0" type="radio"  name="road_eat1_<?php echo e($loop->index + 1); ?>" value="0" <?php if(!$road_map->road_eat1): ?>) checked <?php endif; ?>>
                                <span class="custom-control-description">なし</span>
                            </label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('昼食')); ?></label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_1" type="radio"  name="road_eat2_<?php echo e($loop->index + 1); ?>" value="1" <?php if($road_map->road_eat2): ?> checked <?php endif; ?>>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_0" type="radio"  name="road_eat2_<?php echo e($loop->index + 1); ?>" value="0"  <?php if(!$road_map->road_eat2): ?>) checked <?php endif; ?>>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> <?php echo e(__('夕食')); ?></label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat3_1" type="radio"  name="road_eat3_<?php echo e($loop->index + 1); ?>" value="1" <?php if($road_map->road_eat3): ?> checked <?php endif; ?>>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                    <input id="road_eat3_0" type="radio"  name="road_eat3_<?php echo e($loop->index + 1); ?>" value="0"  <?php if(!$road_map->road_eat3): ?>) checked <?php endif; ?>>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"> 行程表</label>
                            <div class="col-md-6">
                            <!-- <textarea id="codex-editor" type="textarea" class="form-control" name="road_map<?php echo e($loop->index + 1); ?>" rows="10" ><?php echo e(old('road_map',$road_map->road_map)); ?></textarea> -->
                            <textarea id="road_map" type="textarea" class="form-control" name="road_map<?php echo e($loop->index + 1); ?>" rows="10" ><?php echo e($road_map->road_map ? $road_map->road_map : ''); ?></textarea>
                            </div>
                        </div>
                        <script>

                            $('#road_map[name=road_map<?php echo e($loop->index + 1); ?>]').summernote({
                                tabsize: 2,
                                minHeight: 250
                            });
                        // CKEDITOR.replace( 'road_map<?php echo e($loop->index + 1); ?>' );
                        </script>
                    </div>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         
                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name='add-road'>
                                    <?php echo e(__('+ 行程表を追加する')); ?>

                                    <input type="hidden" name="add-road" value="<?php echo e(count($plans-> road_maps)); ?>">
                                </div>
                            </div>
                        </div>
                        <hr />

                        <div class="form-group row mt-5 bg-dark">
                            <label for="" class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 予約受付設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 予約受付タイプ</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="res_type" value="0" <?php if($plans->res_type == 0): ?> checked <?php endif; ?>> <span> 予約</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="res_type" value="2" <?php if($plans->res_type == 2): ?> checked <?php endif; ?>> <span> リクエスト</span>
                            </label>
<!--                             <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="res_type" value="1" <?php if($plans->res_type == 1): ?> checked <?php endif; ?>> <span> 併用</span>
                            </label> -->
                            
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 受付開始日時</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_before_day" value="<?php echo e(old('res_before_day',$plans->res_before_day)); ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_before_type">
                                  <option value="0" <?php if($plans->res_before_type == 0): ?> selected <?php endif; ?>>日前の</option>
                                  <option value="1" <?php if($plans->res_before_type == 1): ?> selected <?php endif; ?>>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_before_time">
                                  <option value="00" <?php if($plans->res_before_time == "00"): ?> selected <?php endif; ?>>00</option>
                                  <option value="01" <?php if($plans->res_before_time == "01"): ?> selected <?php endif; ?>>01</option>
                                  <option value="02" <?php if($plans->res_before_time == "02"): ?> selected <?php endif; ?>>02</option>
                                  <option value="03" <?php if($plans->res_before_time == "03"): ?> selected <?php endif; ?>>03</option>
                                  <option value="04" <?php if($plans->res_before_time == "04"): ?> selected <?php endif; ?>>04</option>
                                  <option value="05" <?php if($plans->res_before_time == "05"): ?> selected <?php endif; ?>>05</option>
                                  <option value="06" <?php if($plans->res_before_time == "06"): ?> selected <?php endif; ?>>06</option>
                                  <option value="07" <?php if($plans->res_before_time == "07"): ?> selected <?php endif; ?>>07</option>
                                  <option value="08" <?php if($plans->res_before_time == "08"): ?> selected <?php endif; ?>>08</option>
                                  <option value="09" <?php if($plans->res_before_time == "09"): ?> selected <?php endif; ?>>09</option>
                                  <option value="10" <?php if($plans->res_before_time == "10"): ?> selected <?php endif; ?>>10</option>
                                  <option value="11" <?php if($plans->res_before_time == "11"): ?> selected <?php endif; ?>>11</option>
                                  <option value="12" <?php if($plans->res_before_time == "12"): ?> selected <?php endif; ?>>12</option>
                                  <option value="13" <?php if($plans->res_before_time == "13"): ?> selected <?php endif; ?>>13</option>
                                  <option value="14" <?php if($plans->res_before_time == "14"): ?> selected <?php endif; ?>>14</option>
                                  <option value="15" <?php if($plans->res_before_time == "15"): ?> selected <?php endif; ?>>15</option>
                                  <option value="16" <?php if($plans->res_before_time == "16"): ?> selected <?php endif; ?>>16</option>
                                  <option value="17" <?php if($plans->res_before_time == "17"): ?> selected <?php endif; ?>>17</option>
                                  <option value="18" <?php if($plans->res_before_time == "18"): ?> selected <?php endif; ?>>18</option>
                                  <option value="19" <?php if($plans->res_before_time == "19"): ?> selected <?php endif; ?>>19</option>
                                  <option value="20" <?php if($plans->res_before_time == "20"): ?> selected <?php endif; ?>>20</option>
                                  <option value="21" <?php if($plans->res_before_time == "21"): ?> selected <?php endif; ?>>21</option>
                                  <option value="22" <?php if($plans->res_before_time == "22"): ?> selected <?php endif; ?>>22</option>
                                  <option value="23" <?php if($plans->res_before_time == "23"): ?> selected <?php endif; ?>>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時から受付を開始する</label>
                        </div> -->
                        <hr />
                        <div class="form-group row mt-4 insert-res-point">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 受付締切日時</label>
                        </div>
                      
                        <div class="form-group row ml-5 res-section2">
                            <div class="col-md-2">
                                <label class="col-form-label text-md-right" style="padding: 0 0 3px 0;">【予約】</label>
                            </div>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_end_day" value="<?php echo e(old('res_end_day',$plans->res_end_day)); ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_end_type">
                                  <option value="0" <?php if($plans->res_end_type == 0): ?> selected <?php endif; ?>>日前の</option>
                                  <option value="1" <?php if($plans->res_end_type == 1): ?> selected <?php endif; ?>>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_end_time">
                                  <option value="00" <?php if($plans->res_end_time == "00"): ?> selected <?php endif; ?>>00</option>
                                  <option value="01" <?php if($plans->res_end_time == "01"): ?> selected <?php endif; ?>>01</option>
                                  <option value="02" <?php if($plans->res_end_time == "02"): ?> selected <?php endif; ?>>02</option>
                                  <option value="03" <?php if($plans->res_end_time == "03"): ?> selected <?php endif; ?>>03</option>
                                  <option value="04" <?php if($plans->res_end_time == "04"): ?> selected <?php endif; ?>>04</option>
                                  <option value="05" <?php if($plans->res_end_time == "05"): ?> selected <?php endif; ?>>05</option>
                                  <option value="06" <?php if($plans->res_end_time == "06"): ?> selected <?php endif; ?>>06</option>
                                  <option value="07" <?php if($plans->res_end_time == "07"): ?> selected <?php endif; ?>>07</option>
                                  <option value="08" <?php if($plans->res_end_time == "08"): ?> selected <?php endif; ?>>08</option>
                                  <option value="09" <?php if($plans->res_end_time == "09"): ?> selected <?php endif; ?>>09</option>
                                  <option value="10" <?php if($plans->res_end_time == "10"): ?> selected <?php endif; ?>>10</option>
                                  <option value="11" <?php if($plans->res_end_time == "11"): ?> selected <?php endif; ?>>11</option>
                                  <option value="12" <?php if($plans->res_end_time == "12"): ?> selected <?php endif; ?>>12</option>
                                  <option value="13" <?php if($plans->res_end_time == "13"): ?> selected <?php endif; ?>>13</option>
                                  <option value="14" <?php if($plans->res_end_time == "14"): ?> selected <?php endif; ?>>14</option>
                                  <option value="15" <?php if($plans->res_end_time == "15"): ?> selected <?php endif; ?>>15</option>
                                  <option value="16" <?php if($plans->res_end_time == "16"): ?> selected <?php endif; ?>>16</option>
                                  <option value="17" <?php if($plans->res_end_time == "17"): ?> selected <?php endif; ?>>17</option>
                                  <option value="18" <?php if($plans->res_end_time == "18"): ?> selected <?php endif; ?>>18</option>
                                  <option value="19" <?php if($plans->res_end_time == "19"): ?> selected <?php endif; ?>>19</option>
                                  <option value="20" <?php if($plans->res_end_time == "20"): ?> selected <?php endif; ?>>20</option>
                                  <option value="21" <?php if($plans->res_end_time == "21"): ?> selected <?php endif; ?>>21</option>
                                  <option value="22" <?php if($plans->res_end_time == "22"): ?> selected <?php endif; ?>>22</option>
                                  <option value="23" <?php if($plans->res_end_time == "23"): ?> selected <?php endif; ?>>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時まで受付する</label>
                        </div>
                       
                        <div class="form-group row ml-5 req-section">

                            <div class="col-md-2">
                                <label class="col-form-label text-md-right" style="padding: 0 0 3px 0;">【リクエスト】</label>
                            </div>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="req_before_day" value="<?php echo e(old('req_before_day',$plans->req_before_day)); ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="req_before_type">
                                  <option value="0" <?php if($plans->req_before_type == 0): ?> selected <?php endif; ?>>日前の</option>
                                  <option value="1" <?php if($plans->req_before_type == 1): ?> selected <?php endif; ?>>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="req_before_time">
                                  <option value="00" <?php if($plans->req_before_time == "00"): ?> selected <?php endif; ?>>00</option>
                                  <option value="01" <?php if($plans->req_before_time == "01"): ?> selected <?php endif; ?>>01</option>
                                  <option value="02" <?php if($plans->req_before_time == "02"): ?> selected <?php endif; ?>>02</option>
                                  <option value="03" <?php if($plans->req_before_time == "03"): ?> selected <?php endif; ?>>03</option>
                                  <option value="04" <?php if($plans->req_before_time == "04"): ?> selected <?php endif; ?>>04</option>
                                  <option value="05" <?php if($plans->req_before_time == "05"): ?> selected <?php endif; ?>>05</option>
                                  <option value="06" <?php if($plans->req_before_time == "06"): ?> selected <?php endif; ?>>06</option>
                                  <option value="07" <?php if($plans->req_before_time == "07"): ?> selected <?php endif; ?>>07</option>
                                  <option value="08" <?php if($plans->req_before_time == "08"): ?> selected <?php endif; ?>>08</option>
                                  <option value="09" <?php if($plans->req_before_time == "09"): ?> selected <?php endif; ?>>09</option>
                                  <option value="10" <?php if($plans->req_before_time == "10"): ?> selected <?php endif; ?>>10</option>
                                  <option value="11" <?php if($plans->req_before_time == "11"): ?> selected <?php endif; ?>>11</option>
                                  <option value="12" <?php if($plans->req_before_time == "12"): ?> selected <?php endif; ?>>12</option>
                                  <option value="13" <?php if($plans->req_before_time == "13"): ?> selected <?php endif; ?>>13</option>
                                  <option value="14" <?php if($plans->req_before_time == "14"): ?> selected <?php endif; ?>>14</option>
                                  <option value="15" <?php if($plans->req_before_time == "15"): ?> selected <?php endif; ?>>15</option>
                                  <option value="16" <?php if($plans->req_before_time == "16"): ?> selected <?php endif; ?>>16</option>
                                  <option value="17" <?php if($plans->req_before_time == "17"): ?> selected <?php endif; ?>>17</option>
                                  <option value="18" <?php if($plans->req_before_time == "18"): ?> selected <?php endif; ?>>18</option>
                                  <option value="19" <?php if($plans->req_before_time == "19"): ?> selected <?php endif; ?>>19</option>
                                  <option value="20" <?php if($plans->req_before_time == "20"): ?> selected <?php endif; ?>>20</option>
                                  <option value="21" <?php if($plans->req_before_time == "21"): ?> selected <?php endif; ?>>21</option>
                                  <option value="22" <?php if($plans->req_before_time == "22"): ?> selected <?php endif; ?>>22</option>
                                  <option value="23" <?php if($plans->req_before_time == "23"): ?> selected <?php endif; ?>>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時まで受付する</label>
                        </div>
                        <hr />

                        <div class="form-group row res-limit-section">
                            <label class="col-md-2 col-form-label text-md-center" >在庫</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_limit_number" value="<?php echo e(old('res_limit_number',$plans->res_limit_number)); ?>">
                            </div>
                            
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2 col-form-label text-md-right">1予約あたり最小人数</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="min_number" value="<?php echo e(old('min_number',$plans->min_number)); ?>">
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">人</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">1予約あたり最大人数	</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="max_number" value="<?php echo e(old('max_number',$plans->max_number)); ?>">
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">人</label>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 支払方法設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 支払方法</label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="card" value="1" <?php if($plans->card == 1): ?> checked <?php endif; ?>> <span> クレジットカード決済</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="cvs" value="1" <?php if($plans->cvs == 1): ?> checked <?php endif; ?>> <span> コンビニ決済</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="prepay" value="1" <?php if($plans->prepay == 1): ?> checked <?php endif; ?>> <span> 事前払い</span>
                            </label>
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="spot" value="1" <?php if($plans->spot == 1): ?> checked <?php endif; ?>> <span> 現地払い</span>
                            </label>
                            
                            
                            
                        </div>
                        <div class="form-group row spot_payment_section">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 現地での支払方法</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="cache_flag" value="<?php if($plans->cache_flag == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->cache_flag == 1): ?> checked <?php endif; ?>> <span> 現地現金払い</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="card_flag" value="<?php if($plans->card_flag == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->card_flag == 1): ?> checked <?php endif; ?>> <span> 現地カード払い</span>
                            </label>
                        </div>
                        <div class="form-group row card_section">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 現地での利用可能な<br />カード</label>
                            <label class="radio-inline">
                              <input class="ml-4" type="checkbox" name="visa" value="<?php if($plans->visa == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->visa == 1): ?> checked <?php endif; ?>> <span> VISA</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="mastercard" value="<?php if($plans->mastercard == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->mastercard == 1): ?> checked <?php endif; ?>> <span> Master Card</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="americanexpress" value="<?php if($plans->americanexpress == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->americanexpress == 1): ?> checked <?php endif; ?>> <span> American Express</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="jcb" value="<?php if($plans->jcb == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->jcb == 1): ?> checked <?php endif; ?>> <span> JCB</span>
                            </label>
                            <label class="radiobinline">
                              <input class="ml-4" type="checkbox" name="unionpay" value="<?php if($plans->unionpay == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->unionpay == 1): ?> checked <?php endif; ?>> <span> 銀聯（ぎんれん）</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="diners" value="<?php if($plans->diners == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->diners == 1): ?> checked <?php endif; ?>> <span> ダイナースクラブ</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="discover" value="<?php if($plans->discover == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->discover == 1): ?> checked <?php endif; ?>> <span> DISCOVER</span>
                            </label>
                        </div>
                        <div class="form-group row card_section">
                            <label class="checkbox-inline offset-md-2">
                              <input class="ml-4" type="checkbox" name="etc_card" value="<?php if($plans->etc_card == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($plans->etc_card == 1): ?> checked <?php endif; ?>> <span> その他（他に利用可能なカードがあれば右に入力）</span>
                            </label>
                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control" name="etc_card_name" value="<?php echo e(old('etc_card_name',$plans->etc_card_name)); ?>" placeholder="※最大50文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 支払方法の補足・詳細</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="payment_comment" rows="2" placeholder="※最大200文字まで"><?php echo e(old('payment_comment',$plans->payment_comment)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 料金設定</span></label>
                        </div>
 <!--                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> プロモーション設定</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="is_discount" value="0" <?php if($plans->is_discount == 0): ?> checked <?php endif; ?>> <span> 通常料金で販売する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="is_discount" value="1" <?php if($plans->is_discount == 1): ?> checked <?php endif; ?>> <span> 割引料金で販売する</span>
                            </label>
                        </div> -->
                        <hr />
                        <?php $__currentLoopData = $plans->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mt-5 price-section-<?php echo e($loop->index + 1); ?>">
                            <div class="form-group row">
                                <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 料金区分 (<?php echo e($loop->index + 1); ?>)</label>
                                <?php if($loop->last && $loop->count != 1): ?>
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-price-<?php echo e($loop->index + 1); ?>' value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この料金区分を削除
                                    </div>
                                </div>
                                <?php elseif(!$loop->first && $loop->count != 1): ?> 
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-price-<?php echo e($loop->index + 1); ?>' style="display: none;" value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この料金区分を削除
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">【料金区分】</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="price_type<?php echo e($loop->index + 1); ?>">
				        <?php $__currentLoopData = $price_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ps->number); ?>" <?php if(old('price_type',$price->type)==$ps->number): ?> selected <?php endif; ?>><?php echo e($ps->name); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                                  <div class="form-group row ml-5">
                                    <table>
                                        <tr>
                                            <th  style="width:50px"></th>
                                            <th style="text-align: center;">大人</th>
                                            <th style="text-align: center;">子供</th>
                                            <th style="text-align: center;">幼児</th>
                                        </tr>
                                        <tr>
                                            <th>A</th>
                                            <td><input type="number" name="price_a_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_a_1_'.($loop->index + 1),$price->a_1)); ?>"></td>
                                            <td><input type="number" name="price_a_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_a_2_'.($loop->index + 1),$price->a_2)); ?>"></td>
                                            <td><input type="number" name="price_a_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_a_3_'.($loop->index + 1),$price->a_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>B</th>
                                            <td><input type="number" name="price_b_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_b_1_'.($loop->index + 1),$price->b_1)); ?>"></td>
                                            <td><input type="number" name="price_b_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_b_2_'.($loop->index + 1),$price->b_2)); ?>"></td>
                                            <td><input type="number" name="price_b_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_b_3_'.($loop->index + 1),$price->b_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>C</th>
                                            <td><input type="number" name="price_c_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_c_1_'.($loop->index + 1),$price->c_1)); ?>"></td>
                                            <td><input type="number" name="price_c_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_c_2_'.($loop->index + 1),$price->c_2)); ?>"></td>
                                            <td><input type="number" name="price_c_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_c_3_'.($loop->index + 1),$price->c_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>D</th>
                                            <td><input type="number" name="price_d_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_d_1_'.($loop->index + 1),$price->d_1)); ?>"></td>
                                            <td><input type="number" name="price_d_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_d_2_'.($loop->index + 1),$price->d_2)); ?>"></td>
                                            <td><input type="number" name="price_d_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_d_3_'.($loop->index + 1),$price->d_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>E</th>
                                            <td><input type="number" name="price_e_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_e_1_'.($loop->index + 1),$price->e_1)); ?>"></td>
                                            <td><input type="number" name="price_e_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_e_2_'.($loop->index + 1),$price->e_2)); ?>"></td>
                                            <td><input type="number" name="price_e_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_e_3_'.($loop->index + 1),$price->e_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>F</th>
                                            <td><input type="number" name="price_f_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_f_1_'.($loop->index + 1),$price->f_1)); ?>"></td>
                                            <td><input type="number" name="price_f_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_f_2_'.($loop->index + 1),$price->f_2)); ?>"></td>
                                            <td><input type="number" name="price_f_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_f_3_'.($loop->index + 1),$price->f_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>G</th>
                                            <td><input type="number" name="price_g_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_g_1_'.($loop->index + 1),$price->g_1)); ?>"></td>
                                            <td><input type="number" name="price_g_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_g_2_'.($loop->index + 1),$price->g_2)); ?>"></td>
                                            <td><input type="number" name="price_g_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_g_3_'.($loop->index + 1),$price->g_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>H</th>
                                            <td><input type="number" name="price_h_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_h_1_'.($loop->index + 1),$price->h_1)); ?>"></td>
                                            <td><input type="number" name="price_h_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_h_2_'.($loop->index + 1),$price->h_2)); ?>"></td>
                                            <td><input type="number" name="price_h_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_h_3_'.($loop->index + 1),$price->h_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>I</th>
                                            <td><input type="number" name="price_i_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_i_1_'.($loop->index + 1),$price->i_1)); ?>"></td>
                                            <td><input type="number" name="price_i_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_i_2_'.($loop->index + 1),$price->i_2)); ?>"></td>
                                            <td><input type="number" name="price_i_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_i_3_'.($loop->index + 1),$price->i_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>J</th>
                                            <td><input type="number" name="price_j_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_j_1_'.($loop->index + 1),$price->j_1)); ?>"></td>
                                            <td><input type="number" name="price_j_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_j_2_'.($loop->index + 1),$price->j_2)); ?>"></td>
                                            <td><input type="number" name="price_j_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_j_3_'.($loop->index + 1),$price->j_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>K</th>
                                            <td><input type="number" name="price_k_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_k_1_'.($loop->index + 1),$price->k_1)); ?>"></td>
                                            <td><input type="number" name="price_k_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_k_2_'.($loop->index + 1),$price->k_2)); ?>"></td>
                                            <td><input type="number" name="price_k_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_k_3_'.($loop->index + 1),$price->k_3)); ?>"></td>
                                        </tr>
                                        <tr>
                                            <th>L</th>
                                            <td><input type="number" name="price_l_1_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_l_1_'.($loop->index + 1),$price->l_1)); ?>"></td>
                                            <td><input type="number" name="price_l_2_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_l_2_'.($loop->index + 1),$price->l_2)); ?>"></td>
                                            <td><input type="number" name="price_l_3_<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_l_3_'.($loop->index + 1),$price->l_3)); ?>"></td>
                                        </tr>
                                    </table>
                                </div>
                       
                          
                            <!-- <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right discount-price" style="padding: 0 0 3px 0;">【割引前の料金】</label>
                                <div class="col-md-3 discount-price">
                                    <input id="name" type="text" class="form-control" name="price_before_price<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_before_price',$price->before_price)); ?>">
                                </div>
                                <label class="col-md-1 col-form-label text-md-left discount-price">円</label>
                                <p class="col-md-6 col-form-label text-md-left discount-comment">※価格が取消し線付きで表示されます　 例）<strike>1,500円〜</strike> 1,000円～　</p>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">【販売料金】</label>
                                <div class="col-md-3">
                                    <input id="name" type="text" class="form-control" name="price_price<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_price',$price->price)); ?>">
                                </div>
                                <label class="col-md-1 col-form-label text-md-left">円</label>
                                <div class="col-md-3">
                                    <input class="" type="checkbox" name="price_week_flag<?php echo e($loop->index + 1); ?>" value="<?php if($price->week_flag == 0): ?><?php echo e(0); ?><?php else: ?><?php echo e(1); ?><?php endif; ?>" <?php if($price->week_flag == 1): ?> checked <?php endif; ?>> <span> 曜日別に料金設定する</span>
                                </div>
                            </div>
                            <div class="form-group row ml-5 week-price-section-<?php echo e($loop->index + 1); ?>">
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 月</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 火</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 水</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 木</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 金</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 土</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 日</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 祝日</label>
                            </div>
                            <div class="form-group row ml-5 week-price-section-<?php echo e($loop->index + 1); ?>">
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_monday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_monday',$price->monday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_tuesday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_tuesday',$price->tuesday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_wednesday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_wednesday',$price->wednesday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_thursday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_thursday',$price->thursday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_friday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_friday',$price->friday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_saturday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_saturday',$price->saturday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_sunday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_sunday',$price->sunday)); ?>">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_holiday<?php echo e($loop->index + 1); ?>" value="<?php echo e(old('price_holiday',$price->holiday)); ?>">
                                </div>
                            </div> -->
                             
                        </div> 
                       
                        <div class="after-price-section-<?php echo e($loop->index + 1); ?>"></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name='add-price'>
                                    <?php echo e(__('+ 料金区分を追加する')); ?>

                                    <input type="hidden" name="add-price" value="1">
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row mt-5">
                            <label class="col-md-2 col-form-label text-md-right"> 料金に含まれるもの</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="included_item" rows="2" placeholder="※最大100文字まで"><?php echo e(old('included_item',$plans->included_item)); ?></textarea>
                            </div>
                        </div>
                        

                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 予約者への質問・注意事項の設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 予約者への質問</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="question_flag" value="0" <?php if($plans->question_flag == 0): ?> checked <?php endif; ?>> <span> 設定しない</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="question_flag" value="1" <?php if($plans->question_flag == 1): ?> checked <?php endif; ?>> <span> 設定する</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="question_content" rows="10" placeholder="※最大1200文字まで"><?php echo e(old('question_content',$plans->question_content)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 予約者からの回答</label>
                            <div class="col-md-6">
                                <select class="form-control" name="answer_flag">
                                  <option value="0" <?php if(old('answer_flag',$plans->answer_flag)==0): ?> selected  <?php endif; ?>>任意</option>
                                  <option value="1" <?php if(old('answer_flag',$plans->answer_flag)==1): ?> selected  <?php endif; ?>>必須</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 注意事項・その他</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="caution_flag" value="0" <?php if($plans->caution_flag == 0): ?> checked <?php endif; ?>> <span> 設定しない</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="caution_flag" value="1" <?php if($plans->caution_flag == 1): ?> checked <?php endif; ?>> <span> 設定する</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="caution_content" rows="10" placeholder="※最大5000文字まで"><?php echo e(old('caution_content',$plans->caution_content)); ?></textarea>
                            </div>
                        </div>
<!--                         <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> その他の項目設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">持ち物</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="item" rows="4" placeholder="※最大1000文字まで"><?php echo e(old('item',$plans->item)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">服装</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="wear" rows="4" placeholder="※最大1000文字まで"><?php echo e(old('wear',$plans->wear)); ?></textarea>
                            </div>
                        </div> -->
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> キャンセル規定</span></label>
                        </div>
                        




                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> キャンセル規定</label>
                            <div class="col-md-8">
                                <textarea id="cancel" type="textarea" class="form-control" name="cancel" rows="4" placeholder="※最大1000文字まで"><?php echo e(old('cancel',$plans->cancel)); ?></textarea>
                            </div>
                        </div>
                        <script>

                                $('#cancel').summernote({
                                    tabsize: 2,
                                    minHeight: 250
                                });

                        // CKEDITOR.replace( 'cancel' );
                        </script>


                        <div class="form-group row mt-5 bg-dark">
                            <label for="" class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 写真設定</span></label>
                        </div>
                        <b style="color: red">※画像アップロード容量は一枚1MBとなります</b>
                        <div class="form-group row">
                            <br>
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> <?php echo e(__('メイン画像')); ?></label>
                            <div id="div-img1" class="col-md-4">
                              <?php if(empty($plans->file_path1)): ?>
                              <input type="file" name="file_path1">
                              <?php else: ?>
                              <img id="img1" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path1); ?>" width="auto" height="150px">
                              <input id="hidden1" type="hidden" name="old_file_path1" value="<?php echo e(old('file_path1',$plans->file_path1)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button1" class="col-md-4 file_path1">
                              <?php if(empty($plans->file_path1)): ?>
                              <input type='button' id="clearbtn1" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(1)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn1" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(1)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像①')); ?></label>
                            <div id="div-img2" class="col-md-4">
                              <?php if(empty($plans->file_path2)): ?>
                              <input type="file" name="file_path2">
                              <?php else: ?>
                              <img id="img2" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path2); ?>" width="auto" height="150px">
                              <input id="hidden2" type="hidden" name="old_file_path2" value="<?php echo e(old('file_path2',$plans->file_path2)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button2" class="col-md-4 file_path2">
                              <?php if(empty($plans->file_path2)): ?>
                              <input type='button' id="clearbtn2" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(2)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn2" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(2)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像②')); ?></label>
                            <div id="div-img3" class="col-md-4">
                              <?php if(empty($plans->file_path3)): ?>
                              <input type="file" name="file_path3">
                              <?php else: ?>
                              <img id="img3" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path3); ?>" width="auto" height="150px">
                              <input id="hidden3" type="hidden" name="old_file_path3" value="<?php echo e(old('file_path3',$plans->file_path3)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button3" class="col-md-4 file_path3">
                              <?php if(empty($plans->file_path3)): ?>
                              <input type='button' id="clearbtn3" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(3)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn3" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(3)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像③')); ?></label>
                            <div id="div-img4" class="col-md-4">
                              <?php if(empty($plans->file_path4)): ?>
                              <input type="file" name="file_path4">
                              <?php else: ?>
                              <img id="img4" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path4); ?>" width="auto" height="150px">
                              <input id="hidden4" type="hidden" name="old_file_path4" value="<?php echo e(old('file_path4',$plans->file_path4)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button4" class="col-md-4 file_path4">
                              <?php if(empty($plans->file_path4)): ?>
                              <input type='button' id="clearbtn4" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(4)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn4" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(4)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像④')); ?></label>
                            <div id="div-img5" class="col-md-4">
                              <?php if(empty($plans->file_path5)): ?>
                              <input type="file" name="file_path5">
                              <?php else: ?>
                              <img id="img5" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path5); ?>" width="auto" height="150px">
                              <input id="hidden5" type="hidden" name="old_file_path5" value="<?php echo e(old('file_path5',$plans->file_path5)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button5" class="col-md-4 file_path5">
                              <?php if(empty($plans->file_path5)): ?>
                              <input type='button' id="clearbtn5" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(5)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn5" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(5)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像⑤')); ?></label>
                            <div id="div-img6" class="col-md-4">
                              <?php if(empty($plans->file_path6)): ?>
                              <input type="file" name="file_path6">
                              <?php else: ?>
                              <img id="img6" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path6); ?>" width="auto" height="150px">
                              <input id="hidden6" type="hidden" name="old_file_path6" value="<?php echo e(old('file_path6',$plans->file_path6)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button6" class="col-md-4 file_path6">
                              <?php if(empty($plans->file_path6)): ?>
                              <input type='button' id="clearbtn6" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(6)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn6" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(6)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像⑥')); ?></label>
                            <div id="div-img7" class="col-md-4">
                              <?php if(empty($plans->file_path7)): ?>
                              <input type="file" name="file_path7">
                              <?php else: ?>
                              <img id="img7" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path7); ?>" width="auto" height="150px">
                              <input id="hidden7" type="hidden" name="old_file_path7" value="<?php echo e(old('file_path7',$plans->file_path7)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button7" class="col-md-4 file_path7">
                              <?php if(empty($plans->file_path7)): ?>
                              <input type='button' id="clearbtn7" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(7)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn7" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(7)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像⑦')); ?></label>
                            <div id="div-img8" class="col-md-4">
                              <?php if(empty($plans->file_path8)): ?>
                              <input type="file" name="file_path8">
                              <?php else: ?>
                              <img id="img8" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path8); ?>" width="auto" height="150px">
                              <input id="hidden8" type="hidden" name="old_file_path8" value="<?php echo e(old('file_path8',$plans->file_path8)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button8" class="col-md-4 file_path8">
                              <?php if(empty($plans->file_path8)): ?>
                              <input type='button' id="clearbtn8" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(8)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn8" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(8)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像⑧')); ?></label>
                            <div id="div-img9" class="col-md-4">
                              <?php if(empty($plans->file_path9)): ?>
                              <input type="file" name="file_path9">
                              <?php else: ?>
                              <img id="img9" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path9); ?>" width="auto" height="150px">
                              <input id="hidden9" type="hidden" name="old_file_path9" value="<?php echo e(old('file_path9',$plans->file_path9)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button9" class="col-md-4 file_path9">
                              <?php if(empty($plans->file_path9)): ?>
                              <input type='button' id="clearbtn9" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(9)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn9" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(9)'/>
                              <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><?php echo e(__('サブ画像⑨')); ?></label>
                            <div id="div-img10" class="col-md-4">
                              <?php if(empty($plans->file_path10)): ?>
                              <input type="file" name="file_path10">
                              <?php else: ?>
                              <img id="img10" src="<?php echo e(config('app.url')); ?>uploads/<?php echo e($plans->file_path10); ?>" width="auto" height="150px">
                              <input id="hidden10" type="hidden" name="old_file_path10" value="<?php echo e(old('file_path10',$plans->file_path10)); ?>">
                              <?php endif; ?>
                            </div>
                            <div id="div-button10" class="col-md-4 file_path10">
                              <?php if(empty($plans->file_path10)): ?>
                              <input type='button' id="clearbtn10" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(10)'/>
                              <?php else: ?>
                              <input type='button' id="deletebtn10" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(10)'/>
                              <?php endif; ?>
                            </div>
                        </div>


                        <hr />
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
<!--
                                <button type="submit" class="btn btn-primary" name='action' value='edit'>
                                    <?php echo e(__('変更する')); ?>

                                </button>
-->
                                <input type="submit" class="btn btn-primary submit" data-action="<?php echo e(config('app.url')); ?>client/plans/update/<?php echo e($plans->id); ?>" value="変更する">
				
				<input type="submit" class="btn btn-warning submit" data-action="<?php echo e(config('app.url')); ?>client/plans/update-tmp/<?php echo e($plans->id); ?>" value="下書き保存">
				
                                <a href="<?php echo e(config('app.url')); ?>client/plans" class="btn btn-secondary">戻る</a>
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
<!-- <link type="text/css" rel="stylesheet" href="<?php echo e(config('app.url')); ?><?php echo e(asset('css/colorbox.css')); ?>" > -->
<link type="text/css" rel="stylesheet" href="<?php echo e(asset('css/colorbox.css')); ?>" >
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="<?php echo e(asset('/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
function PdfDelete() {
    let pdf_val = document.querySelector("#file_path11");
    let pdf_link = document.querySelector("#file_path11_link");
    pdf_val.value = '';
    pdf_val.setAttribute('type', 'file');
    pdf_link.remove();
    document.querySelector(".delete_file").remove();
}
// イベント
$('select[name="repetition_flag"]').change(function() {
    var val = $('select[name="repetition_flag"]').val();
    if(val == '0') {
        $('.week-flag').show();
        $('.month-flag').hide();
    } else {
        $('.week-flag').hide();
        $('.month-flag').show();
    }
});
 // 共通チェックボックス制御
$('input[name="monday"], input[name="tuesday"], input[name="wednesday"], input[name="thursday"], input[name="friday"], input[name="saturday"], input[name="sunday"], input[name="cache_flag"], input[name="card_flag"], input[name="visa"], input[name="mastercard"], input[name="americanexpress"], input[name="jcb"], input[name="unionpay"], input[name="diners"], input[name="discover"], input[name="etc_card"], input[name^="price_week_flag"]').change(function() {
    if ($(this).prop('checked')) {
        $(this).val(1);
        $(this).prop('checked', true);
    } else {
        $(this).val(0);
        $(this).prop('checked', false);
    }
});
$('input[name="question_flag"]').change(function() {
    $(this).prop('checked', true);
    var val = $(this).val();
    if(val == '0') {
        $('textarea[name="question_content"]').prop('disabled', true);
        $('select[name="answer_flag"]').prop('disabled', true);
    } else {
        $('textarea[name="question_content"]').prop('disabled', false);
        $('select[name="answer_flag"]').prop('disabled', false);
    }
});
$('input[name="etc_card"]').change(function() {
    if($(this).prop('checked') == false) {
        $('input[name="etc_card_name"]').prop('disabled', true);
    } else {
        $('input[name="etc_card_name"]').prop('disabled', false);
    }
});
$('input[name="caution_flag"]').change(function() {
    $(this).prop('checked', true);
    var val = $(this).val();
    if(val == '0') {
        $('textarea[name="caution_content"]').prop('disabled', true);
    } else {
        $('textarea[name="caution_content"]').prop('disabled', false);
    }
});
$('input[name="meeting_point_flag"]').change(function() {
    $(this).prop('checked', true);
    var val = $(this).val();
    if(val == 1) {
        $('.meeting_point_section').show();
    } else {
        $('.meeting_point_section').hide();
    }
});
$('select[name="payment_method"]').change(function() {
    $('input[name="card_flag"]').prop('checked', false);
    var val = $(this).val();
    $('.spot_payment_section').hide();
/*
    if(val != 0 && val != 3) {
        $('.spot_payment_section').hide();
        $('.card_section').hide();
    } else {
        $('.spot_payment_section').show();
    }
*/
});
$('input[name="card_flag"]').change(function() {
    if(!$('input[name="card_flag"]').prop('checked')) {
        $('.card_section').hide();
    } else {
        $('.card_section').show();
    }
});
$('input[name="res_limit_flag"]').change(function() {
    $('input[name="res_limit_flag"]').prop('checked');
    var val = $(this).val();
    if (val == 0) {
        $('.switch-limit-number').html('人');
    } else { 
        $('.switch-limit-number').html('件');
    }
});
$('input[name="res_type"]').change(function() {
    $('input[name="res_type"]').prop('checked');
    var val = $(this).val();
    if (val == 2) {
        $('.res-limit-section').hide();
        $('.res-section2').insertBefore('.req-section');
        $('.res-section1').insertBefore('.req-section');
    } else { 
        $('.res-limit-section').show();
        $('.res-section2').insertAfter('.insert-res-point');
        $('.res-section1').insertAfter('.insert-res-point');
    }
});
$('input[name="price_week_flag1"]').change(function() {
    if ($('input[name="price_week_flag1"]').prop('checked')) {
        $('.week-price-section-1').show();
    } else { 
        $('.week-price-section-1').hide();
    }
});
$('input[name="price_week_flag2"]').change(function() {
    if ($('input[name="price_week_flag2"]').prop('checked')) {
        $('.week-price-section-2').show();
    } else { 
        $('.week-price-section-2').hide();
    }
});
$('input[name="price_week_flag3"]').change(function() {
    if ($('input[name="price_week_flag3"]').prop('checked')) {
        $('.week-price-section-3').show();
    } else { 
        $('.week-price-section-3').hide();
    }
});
$('input[name="price_week_flag4"]').change(function() {
    if ($('input[name="price_week_flag4"]').prop('checked')) {
        $('.week-price-section-4').show();
    } else { 
        $('.week-price-section-4').hide();
    }
});
$('input[name="price_week_flag5"]').change(function() {
    if ($('input[name="price_week_flag5"]').prop('checked')) {
        $('.week-price-section-5').show();
    } else { 
        $('.week-price-section-5').hide();
    }
});
$('input[name="price_week_flag6"]').change(function() {
    if ($('input[name="price_week_flag6"]').prop('checked')) {
        $('.week-price-section-6').show();
    } else { 
        $('.week-price-section-6').hide();
    }
});
$('select[name="genre_category"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('select[name="genre_name"]').prop('disabled', true);
    } else {
        $('select[name="genre_name"]').prop('disabled', false);
    }
    $('select[name="genre_name"]').children().remove();
    $('select[name="genre_name"]').append('<option value="0">選択してください</option>');
    if (val == 1) {ajaxGenres('アウトドア');}
    if (val == 2) {ajaxGenres('ウォータースポーツ・マリンスポーツ');}
    if (val == 3) {ajaxGenres('雪・スノースポーツ');}
    if (val == 4) {ajaxGenres('その他スポーツ・フィットネス');}
    if (val == 5) {ajaxGenres('エンタメ・アミューズメント');}
    if (val == 6) {ajaxGenres('レジャー・体験');}
    if (val == 7) {ajaxGenres('クラフト・工芸');}
    if (val == 8) {ajaxGenres('果物・野菜狩り');}
    if (val == 9) {ajaxGenres('ミュージアム・ギャラリー');}
    if (val == 10) {ajaxGenres('伝統文化・日本文化');}
    if (val == 11) {ajaxGenres('乗り物');}
    if (val == 12) {ajaxGenres('風呂・スパ・サロン');}
    if (val == 13) {ajaxGenres('観光施設・名所巡り');}
});
$('input[name="activity_is_overday1"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-1 .overday-section').hide();
    } else {
        $('.activity-section-1 .overday-section').show();
    }
});
$('input[name="activity_is_overday2"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-2 .overday-section').hide();
    } else {
        $('.activity-section-2 .overday-section').show();
    }
});
$('input[name="activity_is_overday3"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-3 .overday-section').hide();
    } else {
        $('.activity-section-3 .overday-section').show();
    }
});
$('input[name="activity_is_overday4"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-4 .overday-section').hide();
    } else {
        $('.activity-section-4 .overday-section').show();
    }
});
$('input[name="activity_period_flag1"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-1 .activity_day_section').hide();
        $('.activity-section-1 .plan-period-section').show();
    } else {
        $('.activity-section-1 .activity_day_section').show();
        $('.activity-section-1 .plan-period-section').hide();
    }
});
$('input[name="activity_period_flag2"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-2 .activity_day_section').hide();
        $('.activity-section-2 .plan-period-section').show();
    } else {
        $('.activity-section-2 .activity_day_section').show();
        $('.activity-section-2 .plan-period-section').hide();
    }
});
$('input[name="activity_period_flag3"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-3 .activity_day_section').hide();
        $('.activity-section-3 .plan-period-section').show();
    } else {
        $('.activity-section-3 .activity_day_section').show();
        $('.activity-section-3 .plan-period-section').hide();
    }
});
$('input[name="activity_period_flag4"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.activity-section-4 .activity_day_section').hide();
        $('.activity-section-4 .plan-period-section').show();
    } else {
        $('.activity-section-4 .activity_day_section').show();
        $('.activity-section-4 .plan-period-section').hide();
    }
});
$('input[name="is_discount"]').change(function() {
    var val = $(this).val();
    if (val == 0) {
        $('.discount-price, .discount-comment').hide();
    } else {
        $('.discount-price, .discount-comment').show();
    }
});
$('div[name="delete-activity-2"]').click(function() {
    $('.activity-section-2, .after-activity-section-2').remove();
    var val = Number($('input[name="add-activity"]').val());
    $('input[name="add-activity"]').val(1);
});
$('div[name="delete-activity-3"]').click(function() {
    $('.activity-section-3, .after-activity-section-3').remove();
    $('div[name="delete-activity-2"]').show();
    var val = Number($('input[name="add-activity"]').val());
    $('input[name="add-activity"]').val(2);
});
$('div[name="delete-activity-4"]').click(function() {
    $('.activity-section-4, .after-activity-section-4').remove();
    $('div[name="delete-activity-3"]').show();
    $('div[name="add-activity"]').show();
    var val = Number($('input[name="add-activity"]').val());
    $('input[name="add-activity"]').val(3);
});
$('div[name="delete-price-2"]').click(function() {
    $('.price-section-2, .after-price-section-2').remove();
    var val = Number($('input[name="add-price"]').val());
    $('input[name="add-price"]').val(1);
});
$('div[name="delete-price-3"]').click(function() {
    $('.price-section-3, .after-price-section-3').remove();
    $('div[name="delete-price-2"]').show();
    var val = Number($('input[name="add-price"]').val());
    $('input[name="add-price"]').val(2);
});
$('div[name="delete-price-4"]').click(function() {
    $('.price-section-4, .after-price-section-4').remove();
    $('div[name="delete-price-3"]').show();
    $('div[name="add-price"]').show();
    var val = Number($('input[name="add-price"]').val());
    $('input[name="add-price"]').val(3);
});
$('div[name="delete-price-5"]').click(function() {
    $('.price-section-5, .after-price-section-5').remove();
    $('div[name="delete-price-4"]').show();
    $('div[name="add-price"]').show();
    var val = Number($('input[name="add-price"]').val());
    $('input[name="add-price"]').val(4);
});
$('div[name="delete-price-6"]').click(function() {
    $('.price-section-6, .after-price-section-6').remove();
    $('div[name="delete-price-5"]').show();
    $('div[name="add-price"]').show();
    var val = Number($('input[name="add-price"]').val());
    $('input[name="add-price"]').val(5);
});
$('div[name="add-activity"]').click(function(e) {
    e.stopPropagation();
    var val = Number($(this).children('input[type="hidden"]').val());
    if (val == 3) {
        $(this).hide();
    }
    $(this).children('input[name="add-activity"]').val(val + 1);
    $(".after-activity-section-" + (val)).load('/html/plan-activity.php', function() {
        $(".added-activity-number-").attr('class', 'added-activity-number-' + val);
        $(".added-activity-number-" + val).text(val + 1);
        $("div.activity-section-").attr('class', 'activity-section-' + (val + 1));
        $(".add-activity-section-" + val).attr('name', 'add-activity-section-' + val);
        $('input[name="activity_name"]').attr('name', 'activity_name' + (val + 1));
        $('input[name="activity_is_overday"]').attr('name', 'activity_is_overday' + (val + 1));
        $('select[name="activity_start_hour"]').attr('name', 'activity_start_hour' + (val + 1));
        $('select[name="activity_start_minute"]').attr('name', 'activity_start_minute' + (val + 1));
        $('select[name="activity_end_hour"]').attr('name', 'activity_end_hour' + (val + 1));
        $('select[name="activity_end_minute"]').attr('name', 'activity_end_minute' + (val + 1));
        $('input[name="activity_days_after"]').attr('name', 'activity_days_after' + (val + 1));
        $('input[name="activity_start_day"]').attr('name', 'activity_start_day' + (val + 1));
        $('input[name="activity_end_day"]').attr('name', 'activity_end_day' + (val + 1));
        $('input[name="activity_period_flag"]').attr('name', 'activity_period_flag' + (val + 1) );
        $(".add-activity-section-" + val).attr('name', 'add-activity-section-' + val);
        $('div[name="delete-activity-' + val + '"]').hide();
        $('div[name="delete-activity-"]').attr('name', 'delete-activity-' + (val + 1));
        $('div[name="delete-activity-2"]').click(function() {
            $('.activity-section-2, .after-activity-section-2').remove();
            var val = Number($('input[name="add-activity"]').val());
            $('input[name="add-activity"]').val(1);
        });
        $('div[name="delete-activity-3"]').click(function() {
            $('.activity-section-3, .after-activity-section-3').remove();
            $('div[name="delete-activity-2"]').show();
            var val = Number($('input[name="add-activity"]').val());
            $('input[name="add-activity"]').val(2);
        });
        $('div[name="delete-activity-4"]').click(function() {
            $('.activity-section-4, .after-activity-section-4').remove();
            $('div[name="delete-activity-3"]').show();
            $('div[name="add-activity"]').show();
            var val = Number($('input[name="add-activity"]').val());
            $('input[name="add-activity"]').val(3);
        });
        $('input[name="activity_is_overday2"]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $('.activity-section-2 .overday-section').hide();
            } else {
                $('.activity-section-2 .overday-section').show();
            }
        });
        $('input[name="activity_is_overday3"]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $('.activity-section-3 .overday-section').hide();
            } else {
                $('.activity-section-3 .overday-section').show();
            }
        });
        $('input[name="activity_is_overday4"]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $('.activity-section-4 .overday-section').hide();
            } else {
                $('.activity-section-4 .overday-section').show();
            }
        });
        $('input[name="activity_period_flag2"]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $('.activity-section-2 .activity_day_section').hide();
                $('.activity-section-2 .plan-period-section').show();
            } else {
                $('.activity-section-2 .activity_day_section').show();
                $('.activity-section-2 .plan-period-section').hide();
            }
        });
        $('input[name="activity_period_flag3"]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $('.activity-section-3 .activity_day_section').hide();
                $('.activity-section-3 .plan-period-section').show();
            } else {
                $('.activity-section-3 .activity_day_section').show();
                $('.activity-section-3 .plan-period-section').hide();
            }
        });
        $('input[name="activity_period_flag4"]').change(function() {
            var val = $(this).val();
            if (val == 0) {
                $('.activity-section-4 .activity_day_section').hide();
                $('.activity-section-4 .plan-period-section').show();
            } else {
                $('.activity-section-4 .activity_day_section').show();
                $('.activity-section-4 .plan-period-section').hide();
            }
        });
/*
        var startday = $('input[name="start_day"]').val(),
            endday = $('input[name="end_day"]').val();
        if (startday) {
            $('.start-day-section').text(startday);
        } else {
            $('.start-day-section').text('未設定');
        }
        if (endday) {
            $('.end-day-section').text(endday);
        } else {
            $('.end-day-section').text('未設定');
        }
*/
    });
    $(".after-activity-section-" + val).after('<div class="after-activity-section-' + (val + 1) + '"></div>');
});
$('div[name="add-price"]').click(function(e) {
    e.stopPropagation();
    var val = Number($(this).children('input[type="hidden"]').val());
    if (val == 5) {
        $(this).hide();
    }
    $(this).children('input[name="add-price"]').val(val + 1);
    $(".after-price-section-" + (val)).load('<?php echo e(config('app.url')); ?>/html/plan-price.php', function() {
        var priceTypes = <?php echo json_encode($price_types, 15, 512) ?>;
        $.each(priceTypes,(i,val) => {
          $('select').append($('<option>').html(val.name).val(val.number));
        });
        if ($('input[name="is_discount"]:checked').val() == 1) {
	    $('.discount-price').show();   
        }
        $(".added-price-number-").attr('class', 'added-price-number-' + val);
        $(".added-price-number-" + val).text(val + 1);
        $("div.price-section-").attr('class', 'mt-5 price-section-' + (val + 1));
        $(".add-price-section-" + val).attr('name', 'add-price-section-' + val);
        $('div[name="delete-price-' + val + '"]').hide();
        $('div[name="delete-price-"]').attr('name', 'delete-price-' + (val + 1));
        $('input[name="price_week_flag"]').attr('name', 'price_week_flag' + (val + 1));
        $('select[name="price_type"]').attr('name', 'price_type' + (val + 1));
        $('input[name="price_before_price"]').attr('name', 'price_before_price' + (val + 1));
        $('input[name="price_price"]').attr('name', 'price_price' + (val + 1));
        $('input[name="price_week_flag"]').attr('name', 'price_week_flag' + (val + 1));
        $('input[name="price_a_1_"]').attr('name', 'price_a_1_' + (val + 1));
        $('input[name="price_a_2_"]').attr('name', 'price_a_2_' + (val + 1));
        $('input[name="price_a_3_"]').attr('name', 'price_a_3_' + (val + 1));
        $('input[name="price_b_1_"]').attr('name', 'price_b_1_' + (val + 1));
        $('input[name="price_b_2_"]').attr('name', 'price_b_2_' + (val + 1));
        $('input[name="price_b_3_"]').attr('name', 'price_b_3_' + (val + 1));
        $('input[name="price_c_1_"]').attr('name', 'price_c_1_' + (val + 1));
        $('input[name="price_c_2_"]').attr('name', 'price_c_2_' + (val + 1));
        $('input[name="price_c_3_"]').attr('name', 'price_c_3_' + (val + 1));
        $('input[name="price_d_1_"]').attr('name', 'price_d_1_' + (val + 1));
        $('input[name="price_d_2_"]').attr('name', 'price_d_2_' + (val + 1));
        $('input[name="price_d_3_"]').attr('name', 'price_d_3_' + (val + 1));
        $('input[name="price_e_1_"]').attr('name', 'price_e_1_' + (val + 1));
        $('input[name="price_e_2_"]').attr('name', 'price_e_2_' + (val + 1));
        $('input[name="price_e_3_"]').attr('name', 'price_e_3_' + (val + 1));
        $('input[name="price_f_1_"]').attr('name', 'price_f_1_' + (val + 1));
        $('input[name="price_f_2_"]').attr('name', 'price_f_2_' + (val + 1));
        $('input[name="price_f_3_"]').attr('name', 'price_f_3_' + (val + 1));
        $('input[name="price_g_1_"]').attr('name', 'price_g_1_' + (val + 1));
        $('input[name="price_g_2_"]').attr('name', 'price_g_2_' + (val + 1));
        $('input[name="price_g_3_"]').attr('name', 'price_g_3_' + (val + 1));
        $('input[name="price_h_1_"]').attr('name', 'price_h_1_' + (val + 1));
        $('input[name="price_h_2_"]').attr('name', 'price_h_2_' + (val + 1));
        $('input[name="price_h_3_"]').attr('name', 'price_h_3_' + (val + 1));
        $('input[name="price_i_1_"]').attr('name', 'price_i_1_' + (val + 1));
        $('input[name="price_i_2_"]').attr('name', 'price_i_2_' + (val + 1));
        $('input[name="price_i_3_"]').attr('name', 'price_i_3_' + (val + 1));
        $('input[name="price_j_1_"]').attr('name', 'price_j_1_' + (val + 1));
        $('input[name="price_j_2_"]').attr('name', 'price_j_2_' + (val + 1));
        $('input[name="price_j_3_"]').attr('name', 'price_j_3_' + (val + 1));
        $('input[name="price_k_1_"]').attr('name', 'price_k_1_' + (val + 1));
        $('input[name="price_k_2_"]').attr('name', 'price_k_2_' + (val + 1));
        $('input[name="price_k_3_"]').attr('name', 'price_k_3_' + (val + 1));
        $('input[name="price_l_1_"]').attr('name', 'price_l_1_' + (val + 1));
        $('input[name="price_l_2_"]').attr('name', 'price_l_2_' + (val + 1));
        $('input[name="price_l_3_"]').attr('name', 'price_l_3_' + (val + 1));
        $("div.week-price-section-").attr('class', 'form-group row week-price-section-' + (val + 1));
        $('div.week-price-section-' + (val + 1)).hide();
        $('div[name="delete-price-2"]').click(function() {
            $('.price-section-2, .after-price-section-2').remove();
            var val = Number($('input[name="add-price"]').val());
            $('input[name="add-price"]').val(1);
        });
        $('div[name="delete-price-3"]').click(function() {
            $('.price-section-3, .after-price-section-3').remove();
            $('div[name="delete-price-2"]').show();
            var val = Number($('input[name="add-price"]').val());
            $('input[name="add-price"]').val(2);
        });
        $('div[name="delete-price-4"]').click(function() {
            $('.price-section-4, .after-price-section-4').remove();
            $('div[name="delete-price-3"]').show();
            $('div[name="add-price"]').show();
            var val = Number($('input[name="add-price"]').val());
            $('input[name="add-price"]').val(3);
        });
        $('div[name="delete-price-5"]').click(function() {
            $('.price-section-5, .after-price-section-5').remove();
            $('div[name="delete-price-4"]').show();
            $('div[name="add-price"]').show();
            var val = Number($('input[name="add-price"]').val());
            $('input[name="add-price"]').val(4);
        });
        $('div[name="delete-price-6"]').click(function() {
            $('.price-section-6, .after-price-section-6').remove();
            $('div[name="delete-price-5"]').show();
            $('div[name="add-price"]').show();
            var val = Number($('input[name="add-price"]').val());
            $('input[name="add-price"]').val(5);
        });
        $('input[name="price_week_flag2"]').change(function() {
            if ($('input[name="price_week_flag2"]').prop('checked')) {
                $('.week-price-section-2').show();
            } else { 
                $('.week-price-section-2').hide();
            }
        });
        $('input[name="price_week_flag3"]').change(function() {
            if ($('input[name="price_week_flag3"]').prop('checked')) {
                $('.week-price-section-3').show();
            } else { 
                $('.week-price-section-3').hide();
            }
        });
        $('input[name="price_week_flag4"]').change(function() {
            if ($('input[name="price_week_flag4"]').prop('checked')) {
                $('.week-price-section-4').show();
            } else { 
                $('.week-price-section-4').hide();
            }
        });
        $('input[name="price_week_flag5"]').change(function() {
            if ($('input[name="price_week_flag5"]').prop('checked')) {
                $('.week-price-section-5').show();
            } else { 
                $('.week-price-section-5').hide();
            }
        });
        $('input[name="price_week_flag6"]').change(function() {
            if ($('input[name="price_week_flag6"]').prop('checked')) {
                $('.week-price-section-6').show();
            } else { 
                $('.week-price-section-6').hide();
            }
        });
    });
    $(".after-price-section-" + val).after('<div class="after-price-section-' + (val + 1) + '"></div>');
});


// ページ読み込み後動作
$(document).ready(function(){
    if ($('select[name="repetition_flag"]').val() == 0) {
        $('.month-flag').hide();
    } else {
        $('.week-flag').hide();
    }
    if ($('#genre_id').val() > 0) {
        var genreid = $('#genre_id').val(),
            genrecategory = $('#genre_id').data('genrecategory');
        ajaxGenres(genrecategory);
        if (genrecategory == 'アウトドア') {$('select[name="genre_category"] option[value="1"]').prop('selected', true);}
        if (genrecategory == 'ウォータースポーツ・マリンスポーツ') {$('select[name="genre_category"] option[value="2"]').prop('selected', true);}
        if (genrecategory == '雪・スノースポーツ') {$('select[name="genre_category"] option[value="3"]').prop('selected', true);}
        if (genrecategory == 'その他スポーツ・フィットネス') {$('select[name="genre_category"] option[value="4"]').prop('selected', true);}
        if (genrecategory == 'エンタメ・アミューズメント') {$('select[name="genre_category"] option[value="5"]').prop('selected', true);}
        if (genrecategory == 'レジャー・体験') {$('select[name="genre_category"] option[value="6"]').prop('selected', true);}
        if (genrecategory == 'クラフト・工芸') {$('select[name="genre_category"] option[value="7"]').prop('selected', true);}
        if (genrecategory == '果物・野菜狩り') {$('select[name="genre_category"] option[value="8"]').prop('selected', true);}
        if (genrecategory == 'ミュージアム・ギャラリー') {$('select[name="genre_category"] option[value="9"]').prop('selected', true);}
        if (genrecategory == '伝統文化・日本文化') {$('select[name="genre_category"] option[value="10"]').prop('selected', true);}
        if (genrecategory == '乗り物') {$('select[name="genre_category"] option[value="11"]').prop('selected', true);}
        if (genrecategory == '風呂・スパ・サロン') {$('select[name="genre_category"] option[value="12"]').prop('selected', true);}
        if (genrecategory == '観光施設・名所巡り') {$('select[name="genre_category"] option[value="13"]').prop('selected', true);}
    }
    if ($('input[name="question_flag"]:checked').val() == 0) {
        $('textarea[name="question_content"]').prop('disabled', true);
        $('select[name="answer_flag"]').prop('disabled', true);
    }
    if ($('input[name="caution_flag"]:checked').val() == 0) {
        $('textarea[name="caution_content"]').prop('disabled', true);
    }
    if ($('input[name="meeting_point_flag"]:checked').val() != 1) {
        $('.meeting_point_section').hide();
    }
    $('.spot_payment_section').hide();
/*
    if ($('select[name="payment_method"]').val() != 0 && $('select[name="payment_method"]').val() != 3) {
        $('.spot_payment_section').hide();
    }
*/
    if (!$('input[name="card_flag"]').prop('checked')) {
        $('.card_section').hide();
    }
    if ($('input[name="etc_card"]').val() == 0) {
        $('input[name="etc_card_name"]').prop('disabled', true);
    }
    if ($('input[name="res_type"]:checked').val() == 2) {
        $('.res-limit-section').hide();
        $('.res-section2').insertBefore('.req-section');
        $('.res-section1').insertBefore('.req-section');
    }
    if ($('input[name="res_limit_flag"]:checked').val() == 1) {
        $('.switch-limit-number').html('件');
    }
    if (!$('input[name="price_week_flag1"]').prop('checked')) {
        $('.week-price-section-1').hide();
    }
    if (!$('input[name="price_week_flag2"]').prop('checked')) {
        $('.week-price-section-2').hide();
    }
    if (!$('input[name="price_week_flag3"]').prop('checked')) {
        $('.week-price-section-3').hide();
    }
    if (!$('input[name="price_week_flag4"]').prop('checked')) {
        $('.week-price-section-4').hide();
    }
    if (!$('input[name="price_week_flag5"]').prop('checked')) {
        $('.week-price-section-5').hide();
    }
    if (!$('input[name="price_week_flag6"]').prop('checked')) {
        $('.week-price-section-6').hide();
    }
/*
    var startday = $('input[name="start_day"]').val(),
        endday = $('input[name="end_day"]').val();
    if (startday) {
        $('.start-day-section').text(startday);
    } else {
        $('.start-day-section').text('未設定');
    }
    if (endday) {
        $('.end-day-section').text(endday);
    } else {
        $('.end-day-section').text('未設定');
    }
*/
    if ($('input[name="activity_is_overday1"]:checked').val() == 0) {
        $('.activity-section-1 .overday-section').hide();
    }
    if ($('input[name="activity_is_overday2"]:checked').val() == 0) {
        $('.activity-section-2 .overday-section').hide();
    }
    if ($('input[name="activity_is_overday3"]:checked').val() == 0) {
        $('.activity-section-3 .overday-section').hide();
    }
    if ($('input[name="activity_is_overday4"]:checked').val() == 0) {
        $('.activity-section-4 .overday-section').hide();
    }
    if ($('input[name="activity_period_flag1"]:checked').val() == 0) {
        $('.activity-section-1 .activity_day_section').hide();
    } else {
        $('.activity-section-1 .plan-period-section').hide();
    }
    if ($('input[name="activity_period_flag2"]:checked').val() == 0) {
        $('.activity-section-2 .activity_day_section').hide();
    } else {
        $('.activity-section-2 .plan-period-section').hide();
    }
    if ($('input[name="activity_period_flag3"]:checked').val() == 0) {
        $('.activity-section-3 .activity_day_section').hide();
    } else {
        $('.activity-section-3 .plan-period-section').hide();
    }
    if ($('input[name="activity_period_flag4"]:checked').val() == 0) {
        $('.activity-section-4 .activity_day_section').hide();
    } else {
        $('.activity-section-4 .plan-period-section').hide();
    }
    if ($('select[name="genre_category"]').val() == 0) {
        $('select[name="genre_name"]').prop('disabled', true);
    }
    if ($('input[name="is_discount"]:checked').val() == 0) {
        $('.discount-price, .discount-comment').hide();
    }
    $('input[name="add-activity"]').val(<?php echo e(count($plans->activities)); ?>);
    $('input[name="add-price"]').val(<?php echo e(count($plans->prices)); ?>);

    var val = $('input[name="add-activity"]').val();
    if (val == 4) {
        $('div[name="add-activity"]').hide();
    }
    var val = $('input[name="add-price"]').val();
    if (val == 6) {
        $('div[name="add-price"]').hide();
    }

    // 地図関連
    $('#map1').colorbox({
        inline: true,
        transition: 'none',
        width: '90%',
        height: '650px',
        onComplete: function(){
            $('#map_canvas').modalMap();
        }
    });
    $(".cboxElement").colorbox({
        inline:true,
        width:"60%",      
    });   
    $('#map2').colorbox({
        inline: true,
        transition: 'none',
        width: '90%',
        height: '650px',
        onComplete: function(){
            $('#map_canvas2').modalMap2();
        }
    });
    $(".cboxElement").colorbox({
        inline:true,
        width:"60%",      
    });   

});

// 関数定義
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

// 送信ボタン切り分け
$('.submit').click(function() {
    $(this).parents('form').attr('action', $(this).data('action'));
    $(this).parents('form').submit();
});

function ajaxGenres(name) {
    $.ajax({
        type: "GET",
        url: "<?php echo e(config('app.url')); ?>client/genres/json/" + name,
        dataType : "json"
    })
    .done(function(data){
        $.each(data, function(i, val){
            $('select[name="genre_name"]').append('<option value="' + val.id + '">' + val.name + '</option>');
        })
        if ($('#genre_id').val() > 0) {
            var genreid = $('#genre_id').val();
            $('select[name="genre_name"] option[value="' + genreid + '"]').prop('selected', true);
        } 
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
    });
}

$('div[name="add-road"]').click(function(e) {
    e.stopPropagation();
    var val = Number($(this).children('input[type="hidden"]').val());
    
    if (val == 5) {
        $(this).hide();
    }
    $(this).children('input[name="add-road"]').val(val + 1);

    $(".after-road-section-" + (val)).load('<?php echo e(config('app.url')); ?>html/plan-road.php', function() {

        if ($('input[name="is_discount"]:checked').val() == 1) {
	    $('.discount-road').show();   
        }
        $(".added-road-number-").attr('class', 'added-road-number-' + val);
        $(".added-road-number-" + val).text(val + 1);
        $("div.road-section-").attr('class', 'mt-5 road-section-' + (val + 1));
        $(".add-road-section-" + val).attr('name', 'add-road-section-' + val);
        $(".schedule").attr('class', 'schedule' + val);
        $(".schedule" + val).text($(".schedule" + val).text() + '(' + (val + 1) + ')');
        $('div[name="delete-road-' + val + '"]').hide();
        $('div[name="delete-road-"]').attr('name', 'delete-road-' + (val + 1));
        $('[name=road_map]').attr('name', 'road_map' + (val + 1));
        $('input[name="road_map_build"]').attr('name', 'road_map_build' + (val + 1));
        $('input[name="road_map_title"]').attr('name', 'road_map_title' + (val + 1));
        $('input[name="road_eat1"]').attr('name', 'road_eat1_' + (val + 1));
        $('input[name="road_eat2"]').attr('name', 'road_eat2_' + (val + 1));
        $('input[name="road_eat3"]').attr('name', 'road_eat3_' + (val + 1));
        $('div[name="delete-road-2"]').click(function() {
            $('.road-section-2, .after-road-section-2').remove();
            var val = Number($('input[name="add-road"]').val());
            $('input[name="add-road"]').val(1);
        });
        $('div[name="delete-road-2"]').click(function() {
            $('.road-section-2, .after-road-section-2').remove();
            var val = Number($('input[name="add-road"]').val());
            $('input[name="add-road"]').val(1);
        });
        $('div[name="delete-road-3"]').click(function() {
            $('.road-section-3, .after-road-section-3').remove();
            $('div[name="delete-road-2"]').show();
            var val = Number($('input[name="add-road"]').val());
            $('input[name="add-road"]').val(2);
        });
        $('div[name="delete-road-4"]').click(function() {
            $('.road-section-4, .after-road-section-4').remove();
            $('div[name="delete-road-3"]').show();
            $('div[name="add-road"]').show();
            var val = Number($('input[name="add-road"]').val());
            $('input[name="add-road"]').val(3);
        });
        $('div[name="delete-road-5"]').click(function() {
            $('.road-section-5, .after-road-section-5').remove();
            $('div[name="delete-road-4"]').show();
            $('div[name="add-road"]').show();
            var val = Number($('input[name="add-road"]').val());
            $('input[name="add-road"]').val(4);
        });
        $('div[name="delete-price-6"]').click(function() {
            $('.price-section-6, .after-price-section-6').remove();
            $('div[name="delete-price-5"]').show();
            $('div[name="add-price"]').show();
            var val = Number($('input[name="add-price"]').val());
            $('input[name="add-price"]').val(5);
        });
        $('input[name="price_week_flag2"]').change(function() {
            if ($('input[name="price_week_flag2"]').prop('checked')) {
                $('.week-price-section-2').show();
            } else { 
                $('.week-price-section-2').hide();
            }
        });
        $('input[name="price_week_flag3"]').change(function() {
            if ($('input[name="price_week_flag3"]').prop('checked')) {
                $('.week-price-section-3').show();
            } else { 
                $('.week-price-section-3').hide();
            }
        });
        $('input[name="price_week_flag4"]').change(function() {
            if ($('input[name="price_week_flag4"]').prop('checked')) {
                $('.week-price-section-4').show();
            } else { 
                $('.week-price-section-4').hide();
            }
        });
        $('input[name="price_week_flag5"]').change(function() {
            if ($('input[name="price_week_flag5"]').prop('checked')) {
                $('.week-price-section-5').show();
            } else { 
                $('.week-price-section-5').hide();
            }
        });
        $('input[name="price_week_flag6"]').change(function() {
            if ($('input[name="price_week_flag6"]').prop('checked')) {
                $('.week-price-section-6').show();
            } else { 
                $('.week-price-section-6').hide();
            }
        });
        $('#road_map[name=road_map'+(val + 1) +']').summernote({
            tabsize: 2,
            minHeight: 250
        });

        // CKEDITOR.replace( 'road_map' + (val + 1) );
    });
    $(".after-road-section-" + val).after('<div class="after-road-section-' + (val + 1) + '"></div>');
});
$('div[name="delete-road-2"]').click(function() {
    $('.road-section-2, .after-road-section-2').remove();
    var val = Number($('input[name="add-road"]').val());
    $('input[name="add-road"]').val(1);
});
$('div[name="delete-road-3"]').click(function() {
    $('.road-section-3, .after-road-section-3').remove();
    $('div[name="delete-road-2"]').show();
    var val = Number($('input[name="add-road"]').val());
    $('input[name="add-road"]').val(2);
});
$('div[name="delete-road-4"]').click(function() {
    $('.road-section-4, .after-road-section-4').remove();
    $('div[name="delete-road-3"]').show();
    $('div[name="add-road"]').show();
    var val = Number($('input[name="add-road"]').val());
    $('input[name="add-road"]').val(3);
});
$('div[name="delete-road-5"]').click(function() {
    $('.road-section-5, .after-road-section-5').remove();
    $('div[name="delete-road-4"]').show();
    $('div[name="add-road"]').show();
    var val = Number($('input[name="add-road"]').val());
    $('input[name="add-road"]').val(4);
});
$('div[name="delete-road-6"]').click(function() {
    $('.road-section-6, .after-road-section-6').remove();
    $('div[name="delete-road-5"]').show();
    $('div[name="add-road"]').show();
    var val = Number($('input[name="add-road"]').val());
    $('input[name="add-road"]').val(5);
});


</script>


<script src='https://maps.google.com/maps/api/js?key=AIzaSyCG9SfPt8adGSdlgWkq8jdbt64mYaPRkaM' type="text/javascript"></script>
<script src="<?php echo e(asset('/js/jquery.colorbox-min.js')); ?>"></script>
<script src="<?php echo e(asset('/js/map.js')); ?>"></script>
<script src="<?php echo e(asset('/js/map2.js')); ?>"></script>
<script src="<?php echo e(asset('/js/ajaxzip3.js')); ?>"></script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Task\crowd\_08_laravel\zensys\resources\views/client/plans/edit.blade.php ENDPATH**/ ?>