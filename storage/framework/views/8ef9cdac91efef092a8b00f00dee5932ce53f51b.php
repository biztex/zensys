<?php $__env->startSection('title', '販売管理'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<script>
    function goSubmit(num){
        $("#form1").prop('action','<?php echo e(config('app.url')); ?>client/stocks/update'+num+'/<?php echo e($default_plan->id); ?>')
        $("#form1").submit();
    }
</script>
<div class="row mb-2">
  <div class="col-sm-6 mt-3">
    <p>販売管理</p>
  </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><small>・対象プランを選択の上、変更したい項目を編集後、「変更する」ボタンを押してください<br>
・料金区分を選択するとプランで設定されている金額を確認できます</small></div>
                <div class="card-header">
                    
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
                    <form action="<?php echo e(config('app.url')); ?>client/stocks/update/<?php echo e($default_plan->id); ?>" method="POST" name="form1" id="form1">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="form-group mt-3 row">
                        <label class="col-md-2 col-form-label text-md-right">対象プラン</label>
                        <div class="col-md-8">
                            <select class="form-control" id="submit_select" name="plan_id">
                              <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($plan->id); ?>" <?php if(old('plan_id',$plan->id)==$default_plan->id): ?> selected  <?php endif; ?>>（ <?php if($plan->is_listed == 0): ?> 休止 <?php else: ?> 掲載中 <?php endif; ?> ）<?php echo e($plan->name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2 mt-2">
                            <small><a href="<?php echo e(config('app.url')); ?>client/plans/edit/<?php echo e($default_plan->id); ?>" target=_blank >(プランを別ページで開く)</a></small>
                        </div>
                    </div>
                    <div class="form-group mt-3 row">
                        <label class="col-md-2 col-form-label text-md-right">料金区分選択</label>
                        <div class="col-md-8">
                            <select class="form-control" id="submit_select2"name="price_type_id">
                              <?php $__currentLoopData = $priceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priceType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($priceType->number); ?>" <?php if($priceType->number==$default_priceType->number): ?> selected  <?php endif; ?>><?php echo e($priceType->name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>->
                        
                    </div>
                </div>
                <div class="card-body">
                <table>
                                        <tr>
                                            <th  style="width:50px"></th>
                                            <th style="text-align: center;">大人</th>
                                            <th style="text-align: center;">子供</th>
                                            <th style="text-align: center;">幼児</th>
                                        </tr>
                                        <tr>
                                            <th>A</th>
                                            <td><?php echo e($price->a_1); ?></td>
                                            <td><?php echo e($price->a_2); ?></td>
                                            <td><?php echo e($price->a_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>B</th>
                                            <td><?php echo e($price->b_1); ?></td>
                                            <td><?php echo e($price->b_2); ?></td>
                                            <td><?php echo e($price->b_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>C</th>
                                            <td><?php echo e($price->c_1); ?></td>
                                            <td><?php echo e($price->c_2); ?></td>
                                            <td><?php echo e($price->c_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>D</th>
                                            <td><?php echo e($price->d_1); ?></td>
                                            <td><?php echo e($price->d_2); ?></td>
                                            <td><?php echo e($price->d_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>E</th>
                                            <td><?php echo e($price->e_1); ?></td>
                                            <td><?php echo e($price->e_2); ?></td>
                                            <td><?php echo e($price->e_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>F</th>
                                            <td><?php echo e($price->f_1); ?></td>
                                            <td><?php echo e($price->f_2); ?></td>
                                            <td><?php echo e($price->f_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>G</th>
                                            <td><?php echo e($price->g_1); ?></td>
                                            <td><?php echo e($price->g_2); ?></td>
                                            <td><?php echo e($price->g_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>H</th>
                                            <td><?php echo e($price->h_1); ?></td>
                                            <td><?php echo e($price->h_2); ?></td>
                                            <td><?php echo e($price->h_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>I</th>
                                            <td><?php echo e($price->i_1); ?></td>
                                            <td><?php echo e($price->i_2); ?></td>
                                            <td><?php echo e($price->i_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>J</th>
                                            <td><?php echo e($price->j_1); ?></td>
                                            <td><?php echo e($price->j_2); ?></td>
                                            <td><?php echo e($price->j_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>K</th>
                                            <td><?php echo e($price->k_1); ?></td>
                                            <td><?php echo e($price->k_2); ?></td>
                                            <td><?php echo e($price->k_3); ?></td>
                                        </tr>
                                        <tr>
                                            <th>L</th>
                                            <td><?php echo e($price->l_1); ?></td>
                                            <td><?php echo e($price->l_2); ?></td>
                                            <td><?php echo e($price->l_3); ?></td>
                                        </tr>
                                    </table>

                </div>

                <div class="card-body">
                <b>期間一括変更　<input type="date" name="start_date">～<input type="date" name="end_date">
                    を
                    <select name="rank1">
                    <option value="">ランク</option>
                    <option value="A" >A</option>
                    <option value="B" >B</option>
                    <option value="C" >C</option>
                    <option value="D" >D</option>
                    <option value="E" >E</option>
                    <option value="F" >F</option>
                    <option value="G" >G</option>
                    <option value="H" >H</option>
                    <option value="I" >I</option>
                    <option value="J" >J</option>
                    <option value="K" >K</option>
                    <option value="L" >L</option>
                    </select>
                    <input type="button" value="更新する" onclick="goSubmit(2)">
                    <br> <br>
                    曜日一括変更　
                    <input type="checkbox">日　
                    <input type="checkbox">月 　
                    <input type="checkbox">火 　
                    <input type="checkbox">水 　
                    <input type="checkbox">木 　
                    <input type="checkbox">金 　
                    <input type="checkbox">土 　
                    を
                    <select>
                    <option value="">ランク</option>
                    <option value="A" >A</option>
                    <option value="B" >B</option>
                    <option value="C" >C</option>
                    <option value="D" >D</option>
                    <option value="E" >E</option>
                    <option value="F" >F</option>
                    <option value="G" >G</option>
                    <option value="H" >H</option>
                    <option value="I" >I</option>
                    <option value="J" >J</option>
                    <option value="K" >K</option>
                    <option value="L" >L</option>
                    </select>
                    <input type="button" value="更新する" onclick="goSubmit(3)"></b>
                    <div class="row">
                        <div class="col-md-1 h4"><a id="prev-month" href=""><i class="fas fa-fw fa-arrow-left"></i></a></div>
                        <div class="col-md-10 center h4 font-weight-bold text-center"><span id="disp-year"><?php echo e($year); ?></span> <small class="font-weight-bold">年</small>　<span id="disp-month"><?php echo e($month); ?></span> <small class="font-weight-bold">月</small></div>
                        <input type="hidden" id="year">
                        <input type="hidden" id="month">
                        <div class="col-md-1 h4 text-right"><a href="" id="next-month"><i class="fas fa-fw fa-arrow-right"></i></a></div>
                    </div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <?php $__currentLoopData = ['日', '月', '火', '水', '木', '金', '土']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayOfWeek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <th class="bg-light text-center"><?php echo e($dayOfWeek); ?></th>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($date->dayOfWeek == 0): ?>
                        <tr>
                        <?php endif; ?>
                          <input type="hidden" id="day" name="days[]" value="<?php echo e(substr($date, 0 ,10)); ?>">
                          <td
                            <?php if($date->month != date('m')): ?>
                            class=""
                            <?php endif; ?>
                          >
                            <p class="h5" style="margin:0"><?php echo e($date->day); ?></p>
                            <hr style="margin:7px 0 7px 0" />
                            <p style="margin:0 0 5px 0"><small>
  
                            <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                <?php if(substr($date, 0 ,10) == $stock->res_date): ?>
                                <select name="rank[]">
                                <option value="">ランク</option>
                                <option value="A" <?php if($stock->rank=="A"): ?> selected <?php endif; ?>>A</option>
                                <option value="B" <?php if($stock->rank=="B"): ?> selected <?php endif; ?>>B</option>
                                <option value="C" <?php if($stock->rank=="C"): ?> selected <?php endif; ?>>C</option>
                                <option value="D" <?php if($stock->rank=="D"): ?> selected <?php endif; ?>>D</option>
                                <option value="E" <?php if($stock->rank=="E"): ?> selected <?php endif; ?>>E</option>
                                <option value="F" <?php if($stock->rank=="F"): ?> selected <?php endif; ?>>F</option>
                                <option value="G" <?php if($stock->rank=="G"): ?> selected <?php endif; ?>>G</option>
                                <option value="H" <?php if($stock->rank=="H"): ?> selected <?php endif; ?>>H</option>
                                <option value="I" <?php if($stock->rank=="I"): ?> selected <?php endif; ?>>I</option>
                                <option value="J" <?php if($stock->rank=="J"): ?> selected <?php endif; ?>>J</option>
                                <option value="K" <?php if($stock->rank=="K"): ?> selected <?php endif; ?>>K</option>
                                <option value="L" <?php if($stock->rank=="L"): ?> selected <?php endif; ?>>L</option>
                                </select>
                                <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($stock)): ?>
                            <?php if(substr($date, 0 ,10) != $stock->res_date): ?>
                                <select name="rank[]">
                                <option value="">ランク</option>
                                <option value="A">A</option>
                                <option value="B">B</option> 
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                                <option value="H">H</option>
                                <option value="I">I</option>
                                <option value="J">J</option>
                                <option value="K">K</option>
                                <option value="L">L</option>
                                </select>
                           <?php endif; ?>
                            
                            <?php else: ?>
                            <select name="rank[]">
                                <option value="">ランク</option>
                                <option value="A">A</option>
                                <option value="B">B</option> 
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="G">G</option>
                                <option value="H">H</option>
                                <option value="I">I</option>
                                <option value="J">J</option>
                                <option value="K">K</option>
                                <option value="L">L</option>
                                </select>

                           <?php endif; ?>
                            </p>
                            <p style="margin:0 0 5px 0"><small>在庫
                            <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php if(isset($stock)): ?>
                              <?php if(substr($date, 0 ,10) == $stock->res_date): ?>
                                <input class="text-right" style="width: 50px" name="limit_number[]" value="<?php echo e($stock->limit_number); ?>" type="text" />
                                <?php break; ?>
                              <?php endif; ?>
                              <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($stock)): ?>
                            <?php if(substr($date, 0 ,10) != $stock->res_date): ?>
                              <input class="text-right" style="width: 50px" name="limit_number[]" value="<?php echo e($default_plan->res_limit_number); ?>" type="text" />
                            <?php endif; ?>
                            <?php else: ?>
                              <input class="text-right" style="width: 50px" name="limit_number[]" value="<?php echo e($default_plan->res_limit_number); ?>" type="text" />
                            <?php endif; ?>
                              /<?php echo e($default_plan->res_limit_number); ?>

                            <?php if($default_plan->res_limit_flag == 0): ?> 人 <?php else: ?> 件 <?php endif; ?>
                            </small></p>

                             <!-- <p style="margin:0 0 5px 0"><small>料金<br>
                            <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php if(isset($stock)): ?>
                              <?php if(substr($date, 0 ,10) == $stock->res_date): ?>
                                    <?php $__currentLoopData = $priceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priceType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $tmp = \App\Models\StockPriceType::where('plan_id', $default_plan->id)->where('res_date', $stock->res_date)->where('price_type_number', $priceType->number)->first();
                                        $price = '';
                                        if($tmp){
                                            $price =$tmp->price;
                                        }
                                    ?>
                                    <b style="font-size:6px"><?php echo e($priceType->name); ?></b><br><input class="text-right" style="width: 50px" name="price_<?php echo e(substr($date, 0 ,10)); ?>_<?php echo e($priceType->number); ?>" value="<?php echo e($price); ?>" type="text" /><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php break; ?>
                              <?php endif; ?>
                              <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($stock)): ?>
                            <?php if(substr($date, 0 ,10) != $stock->res_date): ?>
                                    <?php $__currentLoopData = $priceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priceType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $tmp = \App\Models\StockPriceType::where('plan_id', $default_plan->id)->where('res_date', substr($date, 0 ,10))->where('price_type_number', $priceType->number)->first(); 
                                        $price = '';
                                        if($tmp){
                                            $price =$tmp->price;
                                        }
                                    ?>
                                    <b style="font-size:6px"><?php echo e($priceType->name); ?></b><br><input class="text-right" style="width: 50px" name="price_<?php echo e(substr($date, 0 ,10)); ?>_<?php echo e($priceType->number); ?>" value="<?php echo e($price); ?>" type="text" /><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              
                            <?php endif; ?>
                            <?php else: ?>
                              <?php $__currentLoopData = $priceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priceType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <b style="font-size:6px"><?php echo e($priceType->name); ?></b><br><input class="text-right" style="width: 50px" name="price_<?php echo e(substr($date, 0 ,10)); ?>_<?php echo e($priceType->number); ?>" value="<?php echo e($default_plan->res_price); ?>" type="text" /><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                              円
                            </small></p> -->


                            <small>販売ステータス</small>
                            <select class="form-control" name="is_active[]">
                            <?php
                            $option_count = 0;
                            foreach ($stocks as $stock) {
                                if(isset($stock)) {
                                    if (substr($date, 0 ,10) == $stock->res_date) {
                                        if ($stock->is_active == '1') {
                                             echo'<option value="1" selected>販売</option>';
                                             echo'<option value="0">売止</option>';
                                             $option_count++;
                                        } else if ($stock->is_active == '0') {
                                             echo'<option value="1">販売</option>';
                                             echo'<option value="0" selected>売止</option>';
                                             $option_count++;
                                        }
                                    }
                                }
                            }
                            if ($option_count == 0) {
                                echo'<option value="1">販売</option>';
                                echo'<option value="0" selected>売止</option>';
                            }
                            ?>
                            </select>
<!--                             <small class="mt-3">予約受付タイプ</small>
                            <select class="form-control" name="res_type[]">
                            <?php
                            $option_count = 0;
                            foreach ($stocks as $stock) {
                                if (isset($stock)) {
                                    if (substr($date, 0 ,10) == $stock->res_date) {
                                        if ($stock->res_type == '0') {
                                            echo'<option value="0" selected>即時</option>';
                                            echo'<option value="2">リクエスト</option>';
                                            echo'<option value="1">併用</option>';
                                            $option_count++;
                                        } else if ($stock->res_type == '2') {
                                            echo'<option value="0">即時</option>';
                                            echo'<option value="2" selected>リクエスト</option>';
                                            echo'<option value="1">併用</option>';
                                            $option_count++;
                                        } else if ($stock->res_type == '1') {
                                            echo'<option value="0">即時</option>';
                                            echo'<option value="2">リクエスト</option>';
                                            echo'<option value="1" selected>併用</option>';
                                            $option_count++;
                                        }
                                    }
                                }
                            }
                            if ($option_count == 0) {
                                if ($default_plan->res_type == '0') {
                                    echo'<option value="0" selected>即時</option>';
                                    echo'<option value="2">リクエスト</option>';
                                    echo'<option value="1">併用</option>';
                                } else if ($default_plan->res_type == '2') {
                                    echo'<option value="0">即時</option>';
                                    echo'<option value="2" selected>リクエスト</option>';
                                    echo'<option value="1">併用</option>';
                                } else if ($default_plan->res_type == '1') {
                                    echo'<option value="0">即時</option>';
                                    echo'<option value="2">リクエスト</option>';
                                    echo'<option value="1" selected>併用</option>';
                                }
                            }
                            ?>
                            </select> -->
                          </td>
                        <?php if($date->dayOfWeek == 6): ?>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-5">
                    <button type="submit" class="btn btn-info" name='action' value='edit'>
                        <?php echo e(__('変更する')); ?>

                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/default.js')); ?>"></script>
<script>
$(document).ready(function(){
    $('#year').val($('#disp-year').text());
    $('#month').val($('#disp-month').text());
    var planId = $('#submit_select').val();
    var price_type_id = $('#submit_select2').val();
    if ($('#month').val() == '12') {
        $('#prev-month').attr('href', '<?php echo e(config('app.url')); ?>client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) -1 ) + '/' + planId+ '/' + price_type_id);
        $('#next-month').attr('href', '<?php echo e(config('app.url')); ?>client/stocks/' + (parseInt($('#year').val()) + 1) + '/' + '1' + '/' + planId+ '/' + price_type_id);
    } else if ($('#month').val() == '1'){
        $('#prev-month').attr('href', '<?php echo e(config('app.url')); ?>client/stocks/' + (parseInt($('#year').val()) - 1) + '/' + '12' + '/' + planId+ '/' + price_type_id);
        $('#next-month').attr('href', '<?php echo e(config('app.url')); ?>client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) +1 ) + '/' + planId+ '/' + price_type_id);
    } else {
        $('#prev-month').attr('href', '<?php echo e(config('app.url')); ?>client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) -1 ) + '/' + planId+ '/' + price_type_id);
        $('#next-month').attr('href', '<?php echo e(config('app.url')); ?>client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) +1 ) + '/' + planId+ '/' + price_type_id);
    }
    // プランセレクトしたらPOST
    $("#submit_select").change(function(){
        let planId = $('#submit_select').val();
        window.location.href = '<?php echo e(config('app.url')); ?>client/stocks/' + $('#year').val() + '/' + $('#month').val() + '/' + planId+ '/' + price_type_id;
    });
    // プランセレクトしたらPOST
    $("#submit_select2").change(function(){
        let price_type_id = $('#submit_select2').val();
        window.location.href = '<?php echo e(config('app.url')); ?>client/stocks/' + $('#year').val() + '/' + $('#month').val() + '/' + planId+ '/' + price_type_id;
    });
});

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/client/stocks/index.blade.php ENDPATH**/ ?>