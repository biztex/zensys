@extends('adminlte::page')

@section('title', '販売管理')

@section('content_header')
@stop

@section('content')
<script>
   
    function goSubmit(num){

        let value = [];
        $('.day').each(function(){
            value.push($(this).val());
        });

        $("#week").val(value);

        $("#form1").prop('action','{{config('app.url')}}client/stocks/update'+num+'/{{ $default_plan->id }}')
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
                    {{--成功もしくは失敗のメッセージ--}}
                    @if (Session::has('message'))
                    <div class="alert alert-info">
                        {{ session('message') }}
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                    @endif
                    <form action="{{config('app.url')}}client/stocks/update/{{ $default_plan->id }}" method="POST" name="form1" id="form1">
                    @csrf
                    @method('PUT')
                    <div class="form-group mt-3 row">
                        <label class="col-md-2 col-form-label text-md-right">対象プラン</label>
                        <div class="col-md-8">
                            <select class="form-control" id="submit_select" name="plan_id">
                              @foreach ($plans as $plan)
                              <option value="{{ $plan->id }}" @if(old('plan_id',$plan->id)==$default_plan->id) selected  @endif>（ @if($plan->is_listed == 0) 休止 @else 掲載中 @endif ）{{ $plan->name }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mt-2">
                            <small><a href="{{config('app.url')}}client/plans/edit/{{ $default_plan->id }}" target=_blank >(プランを別ページで開く)</a></small>
                        </div>
                    </div>
                    <div class="form-group mt-3 row">
                        <label class="col-md-2 col-form-label text-md-right">料金区分選択</label>
                        <div class="col-md-8">
                            <select class="form-control" id="submit_select2"name="price_type_id">
                              @foreach ($priceTypes as $priceType)
                              <option value="{{ $priceType->number }}" @if($priceType->number==$default_priceType->number) selected  @endif>{{ $priceType->name }}</option>
                              @endforeach
                            </select>
                        </div>
                        
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
                                            <td>{{ $price->a_1 }}</td>
                                            <td>{{ $price->a_2 }}</td>
                                            <td>{{ $price->a_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>B</th>
                                            <td>{{ $price->b_1 }}</td>
                                            <td>{{ $price->b_2 }}</td>
                                            <td>{{ $price->b_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>C</th>
                                            <td>{{ $price->c_1 }}</td>
                                            <td>{{ $price->c_2 }}</td>
                                            <td>{{ $price->c_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>D</th>
                                            <td>{{ $price->d_1 }}</td>
                                            <td>{{ $price->d_2 }}</td>
                                            <td>{{ $price->d_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>E</th>
                                            <td>{{ $price->e_1 }}</td>
                                            <td>{{ $price->e_2 }}</td>
                                            <td>{{ $price->e_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>F</th>
                                            <td>{{ $price->f_1 }}</td>
                                            <td>{{ $price->f_2 }}</td>
                                            <td>{{ $price->f_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>G</th>
                                            <td>{{ $price->g_1 }}</td>
                                            <td>{{ $price->g_2 }}</td>
                                            <td>{{ $price->g_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>H</th>
                                            <td>{{ $price->h_1 }}</td>
                                            <td>{{ $price->h_2 }}</td>
                                            <td>{{ $price->h_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>I</th>
                                            <td>{{ $price->i_1 }}</td>
                                            <td>{{ $price->i_2 }}</td>
                                            <td>{{ $price->i_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>J</th>
                                            <td>{{ $price->j_1 }}</td>
                                            <td>{{ $price->j_2 }}</td>
                                            <td>{{ $price->j_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>K</th>
                                            <td>{{ $price->k_1 }}</td>
                                            <td>{{ $price->k_2 }}</td>
                                            <td>{{ $price->k_3 }}</td>
                                        </tr>
                                        <tr>
                                            <th>L</th>
                                            <td>{{ $price->l_1 }}</td>
                                            <td>{{ $price->l_2 }}</td>
                                            <td>{{ $price->l_3 }}</td>
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
                    <label for="limit_num" class="col-md-2">在庫 
                    <input type="text" class="form-control col-md-1 d-inline mx-2 " name="limit_num" value=""></label>
                    <input type="button" value="更新する" onclick="goSubmit(2)">
                    <br> <br>
                    曜日一括変更　
                    <input type="checkbox" class="day" name="day[]" value="0" id="sunday">日
                    <input type="checkbox" class="day" name="day[]" value="0" id="monday">月
                    <input type="checkbox" class="day" name="day[]" value="0" id="tuseday">火
                    <input type="checkbox" class="day" name="day[]" value="0" id="wednesday">水
                    <input type="checkbox" class="day" name="day[]" value="0" id="thursday">木
                    <input type="checkbox" class="day" name="day[]" value="0" id="friday">金
                    <input type="checkbox" class="day" name="day[]" value="0" id="saturday">土
                    <input type="hidden" id="week" name="week" value="">
                    を
                    <select  name="rank2">
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
                    <label for="limit_num2" class="col-md-2">在庫 
                    <input type="text" class="form-control col-md-1 d-inline mx-2 " name="limit_num2" value=""></label>
                    <input type="button" value="更新する" onclick="goSubmit(3)"></b>
                    <div class="row">
                        <div class="col-md-1 h4"><a id="prev-month" href=""><i class="fas fa-fw fa-arrow-left"></i></a></div>
                        <div class="col-md-10 center h4 font-weight-bold text-center"><span id="disp-year">{{ $year }}</span> <small class="font-weight-bold">年</small>　<span id="disp-month">{{ $month }}</span> <small class="font-weight-bold">月</small></div>
                        <input type="hidden" id="year">
                        <input type="hidden" id="month">
                        <div class="col-md-1 h4 text-right"><a href="" id="next-month"><i class="fas fa-fw fa-arrow-right"></i></a></div>
                    </div>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                          <th class="bg-light text-center">{{ $dayOfWeek }}</th>
                          @endforeach
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($dates as $date)
                        @if ($date->dayOfWeek == 0)
                        <tr>
                        @endif
                          <input type="hidden" id="day" name="days[]" value="{{ substr($date, 0 ,10) }}">
                          <td
                            @if ($date->month != date('m'))
                            class=""
                            @endif
                          >
                            <p class="h5" style="margin:0">{{ $date->day }}</p>
                            <hr style="margin:7px 0 7px 0" />
                            <p style="margin:0 0 5px 0"><small>
  
                            @foreach ($stocks as $stock) 
                                @if (substr($date, 0 ,10) == $stock->res_date)
                                <select name="rank[]">
                                <option value="">ランク</option>
                                <option value="A" @if($stock->rank=="A") selected @endif>A</option>
                                <option value="B" @if($stock->rank=="B") selected @endif>B</option>
                                <option value="C" @if($stock->rank=="C") selected @endif>C</option>
                                <option value="D" @if($stock->rank=="D") selected @endif>D</option>
                                <option value="E" @if($stock->rank=="E") selected @endif>E</option>
                                <option value="F" @if($stock->rank=="F") selected @endif>F</option>
                                <option value="G" @if($stock->rank=="G") selected @endif>G</option>
                                <option value="H" @if($stock->rank=="H") selected @endif>H</option>
                                <option value="I" @if($stock->rank=="I") selected @endif>I</option>
                                <option value="J" @if($stock->rank=="J") selected @endif>J</option>
                                <option value="K" @if($stock->rank=="K") selected @endif>K</option>
                                <option value="L" @if($stock->rank=="L") selected @endif>L</option>
                                </select>
                                @break
                                @endif
                            @endforeach
                            @isset($stock)
                            @if (substr($date, 0 ,10) != $stock->res_date)
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
                           @endif
                            
                            @else
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

                           @endisset
                            </p>
                            <p style="margin:0 0 5px 0"><small>在庫
                            @foreach ($stocks as $stock)
                              @isset($stock)
                              @if (substr($date, 0 ,10) == $stock->res_date)
                                <input class="text-right" style="width: 50px" name="limit_number[]" value="{{ $stock->limit_number }}" type="text" />
                                @break
                              @endif
                              @endisset
                            @endforeach
                            @isset($stock)
                            @if (substr($date, 0 ,10) != $stock->res_date)
                              <input class="text-right" style="width: 50px" name="limit_number[]" value="{{ $default_plan->res_limit_number }}" type="text" />
                            @endif
                            @else
                              <input class="text-right" style="width: 50px" name="limit_number[]" value="{{ $default_plan->res_limit_number }}" type="text" />
                            @endisset
                              /{{ $default_plan->res_limit_number }}
                            @if($default_plan->res_limit_flag == 0) 人 @else 件 @endif
                            </small></p>

                             <!-- <p style="margin:0 0 5px 0"><small>料金<br>
                            @foreach ($stocks as $stock)
                              @isset($stock)
                              @if (substr($date, 0 ,10) == $stock->res_date)
                                    @foreach ($priceTypes as $priceType)
                                    @php
                                        $tmp = \App\Models\StockPriceType::where('plan_id', $default_plan->id)->where('res_date', $stock->res_date)->where('price_type_number', $priceType->number)->first();
                                        $price = '';
                                        if($tmp){
                                            $price =$tmp->price;
                                        }
                                    @endphp
                                    <b style="font-size:6px">{{$priceType->name}}</b><br><input class="text-right" style="width: 50px" name="price_{{ substr($date, 0 ,10) }}_{{$priceType->number}}" value="{{ $price }}" type="text" /><br>
                                    @endforeach
                                @break
                              @endif
                              @endisset
                            @endforeach
                            @isset($stock)
                            @if (substr($date, 0 ,10) != $stock->res_date)
                                    @foreach ($priceTypes as $priceType)
                                    @php
                                        $tmp = \App\Models\StockPriceType::where('plan_id', $default_plan->id)->where('res_date', substr($date, 0 ,10))->where('price_type_number', $priceType->number)->first(); 
                                        $price = '';
                                        if($tmp){
                                            $price =$tmp->price;
                                        }
                                    @endphp
                                    <b style="font-size:6px">{{$priceType->name}}</b><br><input class="text-right" style="width: 50px" name="price_{{ substr($date, 0 ,10) }}_{{$priceType->number}}" value="{{ $price }}" type="text" /><br>
                                    @endforeach
                              
                            @endif
                            @else
                              @foreach ($priceTypes as $priceType)
                                    <b style="font-size:6px">{{$priceType->name}}</b><br><input class="text-right" style="width: 50px" name="price_{{ substr($date, 0 ,10) }}_{{$priceType->number}}" value="{{ $default_plan->res_price }}" type="text" /><br>
                                    @endforeach
                            @endisset
                              円
                            </small></p> -->


                            <small>販売ステータス</small>
                            <select class="form-control" name="is_active[]">
                            @php
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
                            @endphp
                            </select>
<!--                             <small class="mt-3">予約受付タイプ</small>
                            <select class="form-control" name="res_type[]">
                            @php
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
                            @endphp
                            </select> -->
                          </td>
                        @if ($date->dayOfWeek == 6)
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-5">
                    <button type="submit" class="btn btn-info" name='action' value='edit'>
                        {{ __('変更する') }}
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


@stop
@section('css')
<link rel="stylesheet" href="{{asset('css/style.css')}}">
@stop

@section('js')
<script type="text/javascript" src="{{asset('js/default.js')}}"></script>
<script>
$(document).ready(function(){
    $('#year').val($('#disp-year').text());
    $('#month').val($('#disp-month').text());
    var planId = $('#submit_select').val();
    var price_type_id = $('#submit_select2').val();
    if ($('#month').val() == '12') {
        $('#prev-month').attr('href', '{{config('app.url')}}client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) -1 ) + '/' + planId+ '/' + price_type_id);
        $('#next-month').attr('href', '{{config('app.url')}}client/stocks/' + (parseInt($('#year').val()) + 1) + '/' + '1' + '/' + planId+ '/' + price_type_id);
    } else if ($('#month').val() == '1'){
        $('#prev-month').attr('href', '{{config('app.url')}}client/stocks/' + (parseInt($('#year').val()) - 1) + '/' + '12' + '/' + planId+ '/' + price_type_id);
        $('#next-month').attr('href', '{{config('app.url')}}client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) +1 ) + '/' + planId+ '/' + price_type_id);
    } else {
        $('#prev-month').attr('href', '{{config('app.url')}}client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) -1 ) + '/' + planId+ '/' + price_type_id);
        $('#next-month').attr('href', '{{config('app.url')}}client/stocks/' + $('#year').val() + '/' + (parseInt($('#month').val()) +1 ) + '/' + planId+ '/' + price_type_id);
    }
    // プランセレクトしたらPOST
    $("#submit_select").change(function(){
        let planId = $('#submit_select').val();
        window.location.href = '{{config('app.url')}}client/stocks/' + $('#year').val() + '/' + $('#month').val() + '/' + planId+ '/' + price_type_id;
    });
    // プランセレクトしたらPOST
    $("#submit_select2").change(function(){
        let price_type_id = $('#submit_select2').val();
        window.location.href = '{{config('app.url')}}client/stocks/' + $('#year').val() + '/' + $('#month').val() + '/' + planId+ '/' + price_type_id;
    });

    $("input.day").on('change', function(){
        if($(this).val() == '1') {
            $(this).val(0);
        } else {
            $(this).val(1);
        }
    })
});

</script>
@stop

