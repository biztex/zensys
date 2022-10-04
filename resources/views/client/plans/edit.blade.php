@extends('adminlte::page')

@section('title', 'プラン編集')

@section('content_header')
@stop

@section('content')
<!-- <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script> -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
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
                    {{--成功もしくは失敗のメッセージ--}}
                    @if (Session::has('message'))
                    <div class="alert alert-primary">
                        {{ session('message') }}
                    </div>
                    @endif
                    <div class="col-md-12 mt-2">
                        <a href="{{config('app.url')}}preview?id={{$plans->id}}" target="_blank">プレビュー</a>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </div>
                    @endif
                    <form action="{{config('app.url')}}client/plans/update/{{ $plans->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row mt-3 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 基本設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label for="id" class="col-md-2 col-form-label text-md-right">{{ __('ID') }}</label>
                            <div class="col-md-6">
                                <input id="id" class="input-group-text text-md-left" type="text" name="id" value="{{ old('$plans->id', $plans->id) }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('ツアーコード') }}</label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code" value="{{ old('code',$plans->code) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> サイトカテゴリ</label>
                            <div class="col-md-6">
                                <select class="form-control" name="kind">
                                @foreach($kinds as $kind)
                                  <option value="{{$kind->number}}" @if(old('kind',$plans->kind)==$kind->number) selected  @endif>{{$kind->name}}</option>

                                @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('ステータス') }}</label>
                            <div class="col-md-6">
                                <select class="form-control" name="is_listed">
                                  <option disabled selected>選択してください</option>
                                  <option value="1" @if(old('is_listed',$plans->is_listed)==1) selected  @endif>掲載中</option>
                                  <option value="0" @if(old('is_listed',$plans->is_listed)==0) selected  @endif>休止</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('プラン名') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name',$plans->name) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> キャッチコピー</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control catchphrase" name="catchphrase" rows="5" placeholder="※最大100文字まで" maxlength="100">{{ old('catchphrase',$plans->catchphrase) }}</textarea>
                                <span id="char_count" class="d-block text-lg-right">100/100</span>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> プラン説明文</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control plan_text" name="description" rows="15" placeholder="※最大1200文字まで" maxlength="1200">{{ old('description',$plans->description) }}</textarea>
                                <span id="plan_char_count" class="d-block text-lg-right">1200/1200</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 出発日</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="start_day" value="{{ old('start_day',$plans->start_day) }}">
                            </div>
                            <label class="col-md-1 col-form-label text-md-center">〜</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="end_day" value="{{ old('end_day',$plans->end_day) }}">
                            </div>
                        </div>
                        <!--
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">繰り返し設定</label>
                            <div class="col-md-2">
                                <select class="form-control" name="repetition_flag">
                                  <option value="0" @if(old('repetition_flag',$plans->repetition_flag)==0) selected  @endif>曜日</option>
                                  <option value="1" @if(old('repetition_flag',$plans->repetition_flag)==1) selected  @endif>日にち</option>
                                </select>
                            </div>
                            <p class="col-md-4 col-form-label text-md-left">※販売期間内で有効となります</p>
                        </div>
                        <div class="form-group row ml-5 week-flag">
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="monday" value="@if ($plans->monday == 0){{0}}@else{{1}}@endif" @if ($plans->monday == 1) checked @endif> <span> 月</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="tuesday" value="@if ($plans->tuesday == 0){{0}}@else{{1}}@endif" @if ($plans->tuesday == 1) checked @endif> <span> 火</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="wednesday" value="@if ($plans->wednesday == 0){{0}}@else{{1}}@endif" @if ($plans->wednesday == 1) checked @endif> <span> 水</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="thursday" value="@if ($plans->thursday == 0){{0}}@else{{1}}@endif" @if ($plans->thursday == 1) checked @endif> <span> 木</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="friday" value="@if ($plans->friday == 0){{0}}@else{{1}}@endif" @if ($plans->friday == 1) checked @endif> <span> 金</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="saturday" value="@if ($plans->saturday == 0){{0}}@else{{1}}@endif" @if ($plans->saturday == 1) checked @endif> <span> 土</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="sunday" value="@if ($plans->sunday == 0){{0}}@else{{1}}@endif" @if ($plans->sunday == 1) checked @endif> <span> 日</span>
                            </label>
                        </div>
                        <div class="form-group row ml-5 month-flag">
                            <label class="col-md-1 col-form-label text-md-right ml-5">毎月</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="repetition_day" value="{{ old('repetition_day',$plans->repetition_day) }}">
                            </div>
                            <label class="col-md-1 col-form-label text-md-right">日に設定</label>
                        </div>
                        <div class="form-group row week-flag">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">選択した曜日が<br />祝日の場合</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="holiday_selected" value="1" @if ($plans->holiday_selected == 1) checked @endif> <span> 開催する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="holiday_selected" value="0" @if ($plans->holiday_selected == 0) checked @endif> <span> 開催しない</span>
                            </label>
                        </div>
                        <div class="form-group row week-flag">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">選択していない曜日が<br />祝日の場合</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="holiday_nonselected" value="1" @if ($plans->holiday_nonselected == 1) checked @endif> <span> 開催する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="holiday_nonselected" value="0" @if ($plans->holiday_nonselected == 0) checked @endif> <span> 開催しない</span>
                            </label>
                        </div>
                        -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('目的地') }}</label>
                            <div class="col-md-6">
                                <input id="destination" type="text" class="form-control" name="destination" value="{{ old('destination',$plans->destination) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('お食事') }}</label>
                            <div class="col-md-6">
                                <input id="eat" type="text" class="form-control" name="eat" value="{{ old('eat',$plans->eat) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('旅行日程') }}</label>
                            <div class="col-md-6">
                                <input id="schedule" type="text" class="form-control" name="schedule" value="{{ old('schedule',$plans->schedule) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('最小催行人員') }}</label>
                            <div class="col-md-6">
                                <input id="min_cnt" type="text" class="form-control" name="min_cnt" value="{{ old('min_cnt',$plans->min_cnt) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('添乗員の有無') }}</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="conductor_selected" value="1" @if ($plans->conductor_selected == 1) checked @endif> <span> あり</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="conductor_selected" value="0" @if ($plans->conductor_selected == 0) checked @endif> <span> なし</span>
                            </label>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('受付締切日時') }}</label>
                            <div class="col-md-6">
                                <input id="deadline" type="text" class="form-control" name="deadline" value="{{ old('deadline',$plans->deadline) }}">
                            </div>
                        </div> -->
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('旅行条件書') }}</label>
                            <div class="col-md-6">
                                <div class="col-md-12 p-0">
                                    <input id="notice" type="text" name="notice" class="form-control" value="{{ old('notice',$plans->notice) }}">or
                                </div>

                                <div class="col-md-12 mt-2">

                                    @empty ($plans->file_path11)
                                        <input type="file" class="col-md-6" name="file_path11" id="file_path11">
                                    @else
                                        <a id="file_path11_link" href="{{config('app.url')}}uploads/{{ $plans->file_path11 }}" >{{ $plans->file_path11 }}</a>
                                        <input id="file_path11" type="hidden"  class="col-md-4" name="file_path11_tmp" value="{{ old('file_path6',$plans->file_path11) }}">
                                        <button type="button"class="btn btn-secondary p-1 delete_file" onclick="PdfDelete()">削除</button>
                                    <!-- <input type="checkbox" name="del_fiel11" value="1">削除 -->
                                    @endempty
                                </div>

                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('利用宿泊施設') }}</label>
                            <div class="col-md-6">
                                <textarea id="institution" type="textarea" class="form-control institution" name="institution" rows="5" placeholder="※最大100文字まで" maxlength="100">{{ old('institution',$plans->institution) }}</textarea>
                                <span id="plan_institution_count" class="d-block text-lg-right">100/100</span>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('利用交通機関') }}</label>
                            <div class="col-md-6">
                                <textarea id="transportation" type="textarea" class="form-control transportation" name="transportation" rows="5" placeholder="※最大100文字まで" maxlength="100">{{ old('transportation',$plans->transportation) }}</textarea>
                                <span id="plan_transportation_count" class="d-block text-lg-right">100/100</span>

                            </div>
                        </div>
                        <p class="text-danger helper-pl-7-per">※乗車地、降車地を必須にした場合は同行者情報も必須になります。</p>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('乗車地　設定') }}</label>
                            <div class="col-md-4">
                                <input id="boarding" type="text" class="form-control" name="boarding" value="{{ old('boarding',$plans->boarding) }}" placeholder="カンマ区切り複数">
                            </div>
                            <div class="col-md-2">
                                <select  class="form-control" name="boarding_type">
                                    <option value="1" @if ($plans->boarding_type == 1) selected @endif>必須
                                    <option value="2" @if ($plans->boarding_type == 2) selected @endif>必須ではない
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('降車地　設定') }}</label>
                            <div class="col-md-4">
                                <input id="drop" type="text" class="form-control" name="drop" value="{{ old('drop',$plans->drop) }}" placeholder="カンマ区切り複数">
                            </div>
                            <div class="col-md-2">
                                <select  class="form-control" name="drop_type">
                                    <option value="1" @if ($plans->drop_type == 1) selected @endif>必須
                                    <option value="2" @if ($plans->drop_type == 2) selected @endif>必須ではない
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('同行者情報') }}</label>
                            <div class="col-md-2">
                                <select  class="form-control" name="companion_type">
                                    <option value="2" @if (old('companion_type', $plans->companion_type) == 2) selected @endif>必須ではない
                                    <option value="1" @if (old('companion_type',  $plans->companion_type) == 1) selected @endif>必須
                                </select>
                            </div>
                        </div>

       <!--                  <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">自動延長設定</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="is_autoextend" value="1" @if ($plans->is_autoextend == 1) checked @endif> <span> 自動で延長する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="is_autoextend" value="0" @if ($plans->is_autoextend == 0) checked @endif> <span> 自動で延長しない</span>
                            </label>
                        </div> -->


<!--                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name="add-activity">
                                    {{ __('+ 体験時間を追加する') }}
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
                                    @foreach ($categories as $category)
                                    <option value="{{ $loop->index + 1 }}">{{ $category->category }}</option>
                                    @endforeach
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


                          @foreach ($plans-> road_maps as $road_map)

                         <div class="after-road-section-{{ $loop->index  }}">


                                                    <div class="form-group row">
                                <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 行程表{{ $loop->index + 1 }}日目</label>
                                @if ($loop->last && $loop->count != 1)
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-road-{{ $loop->index + 1 }}' value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この行程表を削除
                                    </div>
                                </div>
                                @elseif (!$loop->first && $loop->count != 1)
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-road-{{ $loop->index + 1 }}' style="display: none;" value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この行程表を削除
                                    </div>
                                </div>
                                @endif
                            </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('タイトル') }}</label>
                            <div class="col-md-6">
                                <input id="road_map_title" type="text" class="form-control" name="road_map_title{{ $loop->index + 1 }}" value="{{ old('road_map_title',$road_map->road_map_title) }}">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('利用宿泊施設') }}</label>
                            <div class="col-md-6">
                                <input id="road_map_build" type="text" class="form-control" name="road_map_build{{ $loop->index + 1 }}" value="{{ old('road_map_build',$road_map->road_map_build) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('朝食') }}</label>
                            <div class="col-md-6 d-flex align-items-center">
                            <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                            <input id="road_eat1_1" type="radio"  name="road_eat1_{{ $loop->index + 1 }}" value="1" @if($road_map->road_eat1) checked @endif>
                                <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                             </label>
                             <label class="custom-control custom-radio pl-2  m-0 font-weight-normal" role="button">
                             <input id="road_eat1_0" type="radio"  name="road_eat1_{{ $loop->index + 1 }}" value="0" @if(!$road_map->road_eat1)) checked @endif>
                                <span class="custom-control-description">なし</span>
                            </label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('昼食') }}</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_1" type="radio"  name="road_eat2_{{ $loop->index + 1 }}" value="1" @if($road_map->road_eat2) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_0" type="radio"  name="road_eat2_{{ $loop->index + 1 }}" value="0"  @if(!$road_map->road_eat2)) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('夕食') }}</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat3_1" type="radio"  name="road_eat3_{{ $loop->index + 1 }}" value="1" @if($road_map->road_eat3) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                    <input id="road_eat3_0" type="radio"  name="road_eat3_{{ $loop->index + 1 }}" value="0"  @if(!$road_map->road_eat3)) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"> 行程表</label>
                            <div class="col-md-6">
                            <!-- <textarea id="codex-editor" type="textarea" class="form-control" name="road_map{{ $loop->index + 1 }}" rows="10" >{{ old('road_map',$road_map->road_map) }}</textarea> -->
                            <textarea id="road_map" type="textarea" class="form-control" name="road_map{{ $loop->index + 1 }}" rows="10" >{{ $road_map->road_map ? $road_map->road_map : '' }}</textarea>
                            </div>
                        </div>
                        <script>
                            $('#road_map[name=road_map{{ $loop->index + 1 }}]').summernote({
                                tabsize: 2,
                                minHeight: 250
                            });
                        </script>
                    </div>
                         @endforeach
                         <div class="after-road-section-{{ count($plans-> road_maps)  }}"></div>

                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name='add-road'>
                                    {{ __('+ 行程表を追加する') }}
                                    <input type="hidden" name="add-road" value="{{ count($plans-> road_maps)  }}">
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
                              <input class="ml-5" type="radio" name="res_type" value="0" @if ($plans->res_type == 0) checked @endif> <span> 予約</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="res_type" value="2" @if ($plans->res_type == 2) checked @endif> <span> リクエスト</span>
                            </label>
<!--                             <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="res_type" value="1" @if ($plans->res_type == 1) checked @endif> <span> 併用</span>
                            </label> -->

                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 受付開始日時</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_before_day" value="{{ old('res_before_day',$plans->res_before_day) }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_before_type">
                                  <option value="0" @if ($plans->res_before_type == 0) selected @endif>日前の</option>
                                  <option value="1" @if ($plans->res_before_type == 1) selected @endif>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_before_time">
                                  <option value="00" @if ($plans->res_before_time == "00") selected @endif>00</option>
                                  <option value="01" @if ($plans->res_before_time == "01") selected @endif>01</option>
                                  <option value="02" @if ($plans->res_before_time == "02") selected @endif>02</option>
                                  <option value="03" @if ($plans->res_before_time == "03") selected @endif>03</option>
                                  <option value="04" @if ($plans->res_before_time == "04") selected @endif>04</option>
                                  <option value="05" @if ($plans->res_before_time == "05") selected @endif>05</option>
                                  <option value="06" @if ($plans->res_before_time == "06") selected @endif>06</option>
                                  <option value="07" @if ($plans->res_before_time == "07") selected @endif>07</option>
                                  <option value="08" @if ($plans->res_before_time == "08") selected @endif>08</option>
                                  <option value="09" @if ($plans->res_before_time == "09") selected @endif>09</option>
                                  <option value="10" @if ($plans->res_before_time == "10") selected @endif>10</option>
                                  <option value="11" @if ($plans->res_before_time == "11") selected @endif>11</option>
                                  <option value="12" @if ($plans->res_before_time == "12") selected @endif>12</option>
                                  <option value="13" @if ($plans->res_before_time == "13") selected @endif>13</option>
                                  <option value="14" @if ($plans->res_before_time == "14") selected @endif>14</option>
                                  <option value="15" @if ($plans->res_before_time == "15") selected @endif>15</option>
                                  <option value="16" @if ($plans->res_before_time == "16") selected @endif>16</option>
                                  <option value="17" @if ($plans->res_before_time == "17") selected @endif>17</option>
                                  <option value="18" @if ($plans->res_before_time == "18") selected @endif>18</option>
                                  <option value="19" @if ($plans->res_before_time == "19") selected @endif>19</option>
                                  <option value="20" @if ($plans->res_before_time == "20") selected @endif>20</option>
                                  <option value="21" @if ($plans->res_before_time == "21") selected @endif>21</option>
                                  <option value="22" @if ($plans->res_before_time == "22") selected @endif>22</option>
                                  <option value="23" @if ($plans->res_before_time == "23") selected @endif>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時から受付を開始する</label>
                        </div> -->
                        <hr />
                        <div class="form-group row mt-4 insert-res-point">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 受付締切日時</label>
                            <p class="text-danger pl-4">※予約受付タイプで選択したほうの受付締切日時をご記入ください。</p>
                        </div>

                        <div class="form-group row ml-5 res-section2">
                            <div class="col-md-2">
                                <label class="col-form-label text-md-right" style="padding: 0 0 3px 0;">【予約】</label>
                            </div>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_end_day" value="{{ old('res_end_day',$plans->res_end_day) }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_end_type">
                                  <option value="0" @if ($plans->res_end_type == 0) selected @endif>日前の</option>
                                  <option value="1" @if ($plans->res_end_type == 1) selected @endif>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_end_time">
                                  <option value="00" @if ($plans->res_end_time == "00") selected @endif>00</option>
                                  <option value="01" @if ($plans->res_end_time == "01") selected @endif>01</option>
                                  <option value="02" @if ($plans->res_end_time == "02") selected @endif>02</option>
                                  <option value="03" @if ($plans->res_end_time == "03") selected @endif>03</option>
                                  <option value="04" @if ($plans->res_end_time == "04") selected @endif>04</option>
                                  <option value="05" @if ($plans->res_end_time == "05") selected @endif>05</option>
                                  <option value="06" @if ($plans->res_end_time == "06") selected @endif>06</option>
                                  <option value="07" @if ($plans->res_end_time == "07") selected @endif>07</option>
                                  <option value="08" @if ($plans->res_end_time == "08") selected @endif>08</option>
                                  <option value="09" @if ($plans->res_end_time == "09") selected @endif>09</option>
                                  <option value="10" @if ($plans->res_end_time == "10") selected @endif>10</option>
                                  <option value="11" @if ($plans->res_end_time == "11") selected @endif>11</option>
                                  <option value="12" @if ($plans->res_end_time == "12") selected @endif>12</option>
                                  <option value="13" @if ($plans->res_end_time == "13") selected @endif>13</option>
                                  <option value="14" @if ($plans->res_end_time == "14") selected @endif>14</option>
                                  <option value="15" @if ($plans->res_end_time == "15") selected @endif>15</option>
                                  <option value="16" @if ($plans->res_end_time == "16") selected @endif>16</option>
                                  <option value="17" @if ($plans->res_end_time == "17") selected @endif>17</option>
                                  <option value="18" @if ($plans->res_end_time == "18") selected @endif>18</option>
                                  <option value="19" @if ($plans->res_end_time == "19") selected @endif>19</option>
                                  <option value="20" @if ($plans->res_end_time == "20") selected @endif>20</option>
                                  <option value="21" @if ($plans->res_end_time == "21") selected @endif>21</option>
                                  <option value="22" @if ($plans->res_end_time == "22") selected @endif>22</option>
                                  <option value="23" @if ($plans->res_end_time == "23") selected @endif>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時まで受付する</label>
                        </div>

                        <div class="form-group row ml-5 req-section">

                            <div class="col-md-2">
                                <label class="col-form-label text-md-right" style="padding: 0 0 3px 0;">【リクエスト】</label>
                            </div>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="req_before_day" value="{{ old('req_before_day',$plans->req_before_day) }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="req_before_type">
                                  <option value="0" @if ($plans->req_before_type == 0) selected @endif>日前の</option>
                                  <option value="1" @if ($plans->req_before_type == 1) selected @endif>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="req_before_time">
                                  <option value="00" @if ($plans->req_before_time == "00") selected @endif>00</option>
                                  <option value="01" @if ($plans->req_before_time == "01") selected @endif>01</option>
                                  <option value="02" @if ($plans->req_before_time == "02") selected @endif>02</option>
                                  <option value="03" @if ($plans->req_before_time == "03") selected @endif>03</option>
                                  <option value="04" @if ($plans->req_before_time == "04") selected @endif>04</option>
                                  <option value="05" @if ($plans->req_before_time == "05") selected @endif>05</option>
                                  <option value="06" @if ($plans->req_before_time == "06") selected @endif>06</option>
                                  <option value="07" @if ($plans->req_before_time == "07") selected @endif>07</option>
                                  <option value="08" @if ($plans->req_before_time == "08") selected @endif>08</option>
                                  <option value="09" @if ($plans->req_before_time == "09") selected @endif>09</option>
                                  <option value="10" @if ($plans->req_before_time == "10") selected @endif>10</option>
                                  <option value="11" @if ($plans->req_before_time == "11") selected @endif>11</option>
                                  <option value="12" @if ($plans->req_before_time == "12") selected @endif>12</option>
                                  <option value="13" @if ($plans->req_before_time == "13") selected @endif>13</option>
                                  <option value="14" @if ($plans->req_before_time == "14") selected @endif>14</option>
                                  <option value="15" @if ($plans->req_before_time == "15") selected @endif>15</option>
                                  <option value="16" @if ($plans->req_before_time == "16") selected @endif>16</option>
                                  <option value="17" @if ($plans->req_before_time == "17") selected @endif>17</option>
                                  <option value="18" @if ($plans->req_before_time == "18") selected @endif>18</option>
                                  <option value="19" @if ($plans->req_before_time == "19") selected @endif>19</option>
                                  <option value="20" @if ($plans->req_before_time == "20") selected @endif>20</option>
                                  <option value="21" @if ($plans->req_before_time == "21") selected @endif>21</option>
                                  <option value="22" @if ($plans->req_before_time == "22") selected @endif>22</option>
                                  <option value="23" @if ($plans->req_before_time == "23") selected @endif>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時まで受付する</label>
                        </div>
                        <hr />

                        <div class="form-group row res-limit-section">
                            <label class="col-md-2 col-form-label text-md-center" ><span class="badge badge-danger">必須</span> 在庫</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_limit_number" value="{{ old('res_limit_number',$plans->res_limit_number) }}">
                            </div>

                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span>1予約あたり最小人数</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="min_number" value="{{ old('min_number',$plans->min_number) }}">
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">人</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">1予約あたり最大人数	</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="max_number" value="{{ old('max_number',$plans->max_number) }}">
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">人</label>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 支払方法設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 支払方法</label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="card" value="1" @if ($plans->card == 1) checked @endif> <span> クレジットカード決済</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="cvs" value="1" @if ($plans->cvs == 1) checked @endif> <span> コンビニ決済</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="prepay" value="1" @if ($plans->prepay == 1) checked @endif> <span> 銀行振込</span>
                            </label>
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="spot" value="1" @if ($plans->spot == 1) checked @endif> <span> 現地払い</span>
                            </label>



                        </div>
                        <div class="form-group row spot_payment_section">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 現地での支払方法</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="cache_flag" value="@if ($plans->cache_flag == 0){{0}}@else{{1}}@endif" @if ($plans->cache_flag == 1) checked @endif> <span> 現地現金払い</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="card_flag" value="@if ($plans->card_flag == 0){{0}}@else{{1}}@endif" @if ($plans->card_flag == 1) checked @endif> <span> 現地カード払い</span>
                            </label>
                        </div>
                        <div class="form-group row card_section">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 現地での利用可能な<br />カード</label>
                            <label class="radio-inline">
                              <input class="ml-4" type="checkbox" name="visa" value="@if ($plans->visa == 0){{0}}@else{{1}}@endif" @if ($plans->visa == 1) checked @endif> <span> VISA</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="mastercard" value="@if ($plans->mastercard == 0){{0}}@else{{1}}@endif" @if ($plans->mastercard == 1) checked @endif> <span> Master Card</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="americanexpress" value="@if ($plans->americanexpress == 0){{0}}@else{{1}}@endif" @if ($plans->americanexpress == 1) checked @endif> <span> American Express</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="jcb" value="@if ($plans->jcb == 0){{0}}@else{{1}}@endif" @if ($plans->jcb == 1) checked @endif> <span> JCB</span>
                            </label>
                            <label class="radiobinline">
                              <input class="ml-4" type="checkbox" name="unionpay" value="@if ($plans->unionpay == 0){{0}}@else{{1}}@endif" @if ($plans->unionpay == 1) checked @endif> <span> 銀聯（ぎんれん）</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="diners" value="@if ($plans->diners == 0){{0}}@else{{1}}@endif" @if ($plans->diners == 1) checked @endif> <span> ダイナースクラブ</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="discover" value="@if ($plans->discover == 0){{0}}@else{{1}}@endif" @if ($plans->discover == 1) checked @endif> <span> DISCOVER</span>
                            </label>
                        </div>
                        <div class="form-group row card_section">
                            <label class="checkbox-inline offset-md-2">
                              <input class="ml-4" type="checkbox" name="etc_card" value="@if ($plans->etc_card == 0){{0}}@else{{1}}@endif" @if ($plans->etc_card == 1) checked @endif> <span> その他（他に利用可能なカードがあれば右に入力）</span>
                            </label>
                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control" name="etc_card_name" value="{{ old('etc_card_name',$plans->etc_card_name) }}" placeholder="※最大50文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 支払方法の補足・詳細</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control payment_comment" name="payment_comment" maxlength="200" rows="2" placeholder="※最大200文字まで">{{ old('payment_comment',$plans->payment_comment) }}</textarea>
                                <span id="plan_comment_count" class="d-block text-lg-right">200/200</span>

                            </div>
                        </div>

                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 支払期限設定</span></label>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 申込日＋</label>
                            <div class="col-md-2">
                                <input id="name" type="text" class="form-control" name="payment_plus_day" value="{{old('payment_plus_day', $plans->payment_plus_day)}}">
                            </div>
                            <label class="col-md-1 col-form-label text-md-left">日</label>

                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 最終締切日</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="payment_final_deadline" value="{{old('payment_final_deadline', $plans->payment_final_deadline)}}">
                            </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 料金設定</span></label>
                        </div>
 <!--                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> プロモーション設定</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="is_discount" value="0" @if ($plans->is_discount == 0) checked @endif> <span> 通常料金で販売する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="is_discount" value="1" @if ($plans->is_discount == 1) checked @endif> <span> 割引料金で販売する</span>
                            </label>
                        </div> -->
                        <hr />
                        @foreach ($plans->prices as $price)
                        <div class="mt-5 price-section-{{ $loop->index + 1 }}">
                            <div class="form-group row">
                                <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 料金区分 ({{ $loop->index + 1 }})</label>
                                @if ($loop->last && $loop->count != 1)
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-price-{{ $loop->index + 1 }}' value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この料金区分を削除
                                    </div>
                                </div>
                                @elseif (!$loop->first && $loop->count != 1)
                                <div class="col-md-3">
                                    <div type="" class="btn btn-default" name='delete-price-{{ $loop->index + 1 }}' style="display: none;" value=''>
                                        <i class="fas fa-fw fa-window-close"></i> この料金区分を削除
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">【料金区分】</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="price_type{{ $loop->index + 1 }}">
				        @foreach ($price_types as $i => $ps)
                                        <option value="{{ $ps->number }}" @if(old('price_type',$price->type)==$ps->number) selected @endif>{{ $ps->name }}</option>
					@endforeach
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
                                            <td><input type="number" name="price_a_1_{{ $loop->index + 1 }}" value="{{ old('price_a_1_'.($loop->index + 1),$price->a_1) }}"></td>
                                            <td><input type="number" name="price_a_2_{{ $loop->index + 1 }}" value="{{ old('price_a_2_'.($loop->index + 1),$price->a_2) }}"></td>
                                            <td><input type="number" name="price_a_3_{{ $loop->index + 1 }}" value="{{ old('price_a_3_'.($loop->index + 1),$price->a_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>B</th>
                                            <td><input type="number" name="price_b_1_{{ $loop->index + 1 }}" value="{{ old('price_b_1_'.($loop->index + 1),$price->b_1) }}"></td>
                                            <td><input type="number" name="price_b_2_{{ $loop->index + 1 }}" value="{{ old('price_b_2_'.($loop->index + 1),$price->b_2) }}"></td>
                                            <td><input type="number" name="price_b_3_{{ $loop->index + 1 }}" value="{{ old('price_b_3_'.($loop->index + 1),$price->b_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>C</th>
                                            <td><input type="number" name="price_c_1_{{ $loop->index + 1 }}" value="{{ old('price_c_1_'.($loop->index + 1),$price->c_1) }}"></td>
                                            <td><input type="number" name="price_c_2_{{ $loop->index + 1 }}" value="{{ old('price_c_2_'.($loop->index + 1),$price->c_2) }}"></td>
                                            <td><input type="number" name="price_c_3_{{ $loop->index + 1 }}" value="{{ old('price_c_3_'.($loop->index + 1),$price->c_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>D</th>
                                            <td><input type="number" name="price_d_1_{{ $loop->index + 1 }}" value="{{ old('price_d_1_'.($loop->index + 1),$price->d_1) }}"></td>
                                            <td><input type="number" name="price_d_2_{{ $loop->index + 1 }}" value="{{ old('price_d_2_'.($loop->index + 1),$price->d_2) }}"></td>
                                            <td><input type="number" name="price_d_3_{{ $loop->index + 1 }}" value="{{ old('price_d_3_'.($loop->index + 1),$price->d_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>E</th>
                                            <td><input type="number" name="price_e_1_{{ $loop->index + 1 }}" value="{{ old('price_e_1_'.($loop->index + 1),$price->e_1) }}"></td>
                                            <td><input type="number" name="price_e_2_{{ $loop->index + 1 }}" value="{{ old('price_e_2_'.($loop->index + 1),$price->e_2) }}"></td>
                                            <td><input type="number" name="price_e_3_{{ $loop->index + 1 }}" value="{{ old('price_e_3_'.($loop->index + 1),$price->e_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>F</th>
                                            <td><input type="number" name="price_f_1_{{ $loop->index + 1 }}" value="{{ old('price_f_1_'.($loop->index + 1),$price->f_1) }}"></td>
                                            <td><input type="number" name="price_f_2_{{ $loop->index + 1 }}" value="{{ old('price_f_2_'.($loop->index + 1),$price->f_2) }}"></td>
                                            <td><input type="number" name="price_f_3_{{ $loop->index + 1 }}" value="{{ old('price_f_3_'.($loop->index + 1),$price->f_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>G</th>
                                            <td><input type="number" name="price_g_1_{{ $loop->index + 1 }}" value="{{ old('price_g_1_'.($loop->index + 1),$price->g_1) }}"></td>
                                            <td><input type="number" name="price_g_2_{{ $loop->index + 1 }}" value="{{ old('price_g_2_'.($loop->index + 1),$price->g_2) }}"></td>
                                            <td><input type="number" name="price_g_3_{{ $loop->index + 1 }}" value="{{ old('price_g_3_'.($loop->index + 1),$price->g_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>H</th>
                                            <td><input type="number" name="price_h_1_{{ $loop->index + 1 }}" value="{{ old('price_h_1_'.($loop->index + 1),$price->h_1) }}"></td>
                                            <td><input type="number" name="price_h_2_{{ $loop->index + 1 }}" value="{{ old('price_h_2_'.($loop->index + 1),$price->h_2) }}"></td>
                                            <td><input type="number" name="price_h_3_{{ $loop->index + 1 }}" value="{{ old('price_h_3_'.($loop->index + 1),$price->h_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>I</th>
                                            <td><input type="number" name="price_i_1_{{ $loop->index + 1 }}" value="{{ old('price_i_1_'.($loop->index + 1),$price->i_1) }}"></td>
                                            <td><input type="number" name="price_i_2_{{ $loop->index + 1 }}" value="{{ old('price_i_2_'.($loop->index + 1),$price->i_2) }}"></td>
                                            <td><input type="number" name="price_i_3_{{ $loop->index + 1 }}" value="{{ old('price_i_3_'.($loop->index + 1),$price->i_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>J</th>
                                            <td><input type="number" name="price_j_1_{{ $loop->index + 1 }}" value="{{ old('price_j_1_'.($loop->index + 1),$price->j_1) }}"></td>
                                            <td><input type="number" name="price_j_2_{{ $loop->index + 1 }}" value="{{ old('price_j_2_'.($loop->index + 1),$price->j_2) }}"></td>
                                            <td><input type="number" name="price_j_3_{{ $loop->index + 1 }}" value="{{ old('price_j_3_'.($loop->index + 1),$price->j_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>K</th>
                                            <td><input type="number" name="price_k_1_{{ $loop->index + 1 }}" value="{{ old('price_k_1_'.($loop->index + 1),$price->k_1) }}"></td>
                                            <td><input type="number" name="price_k_2_{{ $loop->index + 1 }}" value="{{ old('price_k_2_'.($loop->index + 1),$price->k_2) }}"></td>
                                            <td><input type="number" name="price_k_3_{{ $loop->index + 1 }}" value="{{ old('price_k_3_'.($loop->index + 1),$price->k_3) }}"></td>
                                        </tr>
                                        <tr>
                                            <th>L</th>
                                            <td><input type="number" name="price_l_1_{{ $loop->index + 1 }}" value="{{ old('price_l_1_'.($loop->index + 1),$price->l_1) }}"></td>
                                            <td><input type="number" name="price_l_2_{{ $loop->index + 1 }}" value="{{ old('price_l_2_'.($loop->index + 1),$price->l_2) }}"></td>
                                            <td><input type="number" name="price_l_3_{{ $loop->index + 1 }}" value="{{ old('price_l_3_'.($loop->index + 1),$price->l_3) }}"></td>
                                        </tr>
                                    </table>
                                </div>


                            <!-- <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right discount-price" style="padding: 0 0 3px 0;">【割引前の料金】</label>
                                <div class="col-md-3 discount-price">
                                    <input id="name" type="text" class="form-control" name="price_before_price{{ $loop->index + 1 }}" value="{{ old('price_before_price',$price->before_price) }}">
                                </div>
                                <label class="col-md-1 col-form-label text-md-left discount-price">円</label>
                                <p class="col-md-6 col-form-label text-md-left discount-comment">※価格が取消し線付きで表示されます　 例）<strike>1,500円〜</strike> 1,000円～　</p>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">【販売料金】</label>
                                <div class="col-md-3">
                                    <input id="name" type="text" class="form-control" name="price_price{{ $loop->index + 1 }}" value="{{ old('price_price',$price->price) }}">
                                </div>
                                <label class="col-md-1 col-form-label text-md-left">円</label>
                                <div class="col-md-3">
                                    <input class="" type="checkbox" name="price_week_flag{{ $loop->index + 1 }}" value="@if ($price->week_flag == 0){{0}}@else{{1}}@endif" @if ($price->week_flag == 1) checked @endif> <span> 曜日別に料金設定する</span>
                                </div>
                            </div>
                            <div class="form-group row ml-5 week-price-section-{{ $loop->index + 1 }}">
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 月</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 火</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 水</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 木</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 金</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 土</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 日</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 祝日</label>
                            </div>
                            <div class="form-group row ml-5 week-price-section-{{ $loop->index + 1 }}">
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_monday{{ $loop->index + 1 }}" value="{{ old('price_monday',$price->monday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_tuesday{{ $loop->index + 1 }}" value="{{ old('price_tuesday',$price->tuesday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_wednesday{{ $loop->index + 1 }}" value="{{ old('price_wednesday',$price->wednesday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_thursday{{ $loop->index + 1 }}" value="{{ old('price_thursday',$price->thursday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_friday{{ $loop->index + 1 }}" value="{{ old('price_friday',$price->friday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_saturday{{ $loop->index + 1 }}" value="{{ old('price_saturday',$price->saturday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_sunday{{ $loop->index + 1 }}" value="{{ old('price_sunday',$price->sunday) }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_holiday{{ $loop->index + 1 }}" value="{{ old('price_holiday',$price->holiday) }}">
                                </div>
                            </div> -->

                        </div>

                        <div class="after-price-section-{{ $loop->index + 1 }}"></div>
                        @endforeach
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name='add-price'>
                                    {{ __('+ 料金区分を追加する') }}
                                    <input type="hidden" name="add-price" value="1">
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row mt-5">
                            <label class="col-md-2 col-form-label text-md-right"> 料金に含まれるもの</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control included_item" name="included_item" maxlength="100" rows="2" placeholder="※最大100文字まで">{{ old('included_item',$plans->included_item) }}</textarea>
                                <span id="plan_include_count" class="d-block text-lg-right">100/100</span>
                            </div>
                        </div>


                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 予約者への質問・注意事項の設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 予約者への質問</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="question_flag" value="0" @if ($plans->question_flag == 0) checked @endif> <span> 設定しない</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="question_flag" value="1" @if ($plans->question_flag == 1) checked @endif> <span> 設定する</span>
                            </label>
                        </div>

                        @if($plans->question_flag == 1)
                            @if( $plans->question_content != "null" )
                                @if(json_decode($plans->question_content , true) == null)
                                    <div class="after-question-section-0">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label text-md-right"></label>
                                            <div class="col-md-6">
                                                <textarea id="name" type="textarea" class="form-control question_content" maxlength="1200" name="question_content[]" rows="10" placeholder="※最大1200文字まで">{{ old('question_content[]',$plans->question_content ) }}</textarea>
                                                <span class="d-block text-lg-right plan_question_count" class="d-block text-lg-right">1200/1200</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="after-question-section-1"></div>
                                    <div class="form-group row mb-4">
                                        <div class="col-md-6 offset-md-4">
                                            <div type="" class="btn btn-default" name="add-question">
                                                + 質問を追加する
                                                <input type="hidden" name="add-question" value="1">
                                            </div>
                                        </div>
                                    </div>
                                @else

                                    @foreach(json_decode($plans->question_content , true) as $content)
                                    <div class="after-question-section-{{$loop->index }}">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label text-md-right"></label>
                                        <div class="col-md-6">
                                            @if ($loop->last && $loop->count != 1)
                                            <div class="col-md-6 mb-2">
                                                <div type="" class="btn btn-default delete-plan" name='delete-plan' value=''>
                                                    <i class="fas fa-fw fa-window-close"></i> この質問を削除
                                                </div>
                                            </div>
                                            @elseif (!$loop->first && $loop->count != 1)
                                            <div class="col-md-6 mb-2">
                                                <div type="" class="btn btn-default delete-plan" name='delete-plan' style="display: none;" value=''>
                                                    <i class="fas fa-fw fa-window-close"></i> この質問を削除
                                                </div>
                                            </div>
                                            @endif

                                            <textarea id="name" type="textarea" class="form-control question_content" maxlength="1200" name="question_content[]" rows="10" placeholder="※最大1200文字まで">{{ old('question_content[]',$content ) }}</textarea>
                                            <span class="d-block text-lg-right plan_question_count" class="d-block text-lg-right">1200/1200</span>
                                        </div>
                                    </div>
                                    </div>
                                    @endforeach 
                                    <div class="after-question-section-{{ count(json_decode($plans->question_content , true)) }}"></div>
                                    <div class="form-group row mb-4">
                                        <div class="col-md-6 offset-md-4">
                                            <div type="" class="btn btn-default" name="add-question">
                                                + 質問を追加する
                                                <input type="hidden" name="add-question" value="{{ count(json_decode($plans->question_content , true))}}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else

                                

                                <div class="after-question-section-0">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label text-md-right"></label>
                                            <div class="col-md-6">
                                                <textarea id="name" type="textarea" class="form-control question_content" maxlength="1200" name="question_content[]" rows="10" placeholder="※最大1200文字まで" disabled="">{{ old('question_content[]') }}</textarea>
                                                <span class="d-block text-lg-right plan_question_count" class="d-block text-lg-right">1200/1200</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="after-question-section-1"></div>
                                    <div class="form-group row mb-4">
                                        <div class="col-md-6 offset-md-4">
                                            <div type="" class="btn btn-default" name="add-question" style="display: none">
                                                + 質問を追加する
                                                <input type="hidden" name="add-question" value="1">
                                            </div>
                                        </div>
                                    </div>
                            
                            @endif
                        @else
                                <div class="after-question-section-0">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label text-md-right"></label>
                                            <div class="col-md-6">
                                                <textarea id="name" type="textarea" class="form-control question_content" maxlength="1200" name="question_content[]" rows="10" placeholder="※最大1200文字まで" disabled="">{{ old('question_content[]') }}</textarea>
                                                <span class="d-block text-lg-right plan_question_count" class="d-block text-lg-right">1200/1200</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="after-question-section-1"></div>
                                    <div class="form-group row mb-4">
                                        <div class="col-md-6 offset-md-4">
                                            <div type="" class="btn btn-default" name="add-question" style="display: none">
                                                + 質問を追加する
                                                <input type="hidden" name="add-question" value="1">
                                            </div>
                                        </div>
                                    </div>
                        @endif

                        
                      
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 予約者からの回答</label>
                            <div class="col-md-6">
                                <select class="form-control" name="answer_flag">
                                  <option value="0" @if(old('answer_flag',$plans->answer_flag)==0) selected  @endif>任意</option>
                                  <option value="1" @if(old('answer_flag',$plans->answer_flag)==1) selected  @endif>必須</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 注意事項・その他</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="caution_flag" value="0" @if ($plans->caution_flag == 0) checked @endif> <span> 設定しない</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="caution_flag" value="1" @if ($plans->caution_flag == 1) checked @endif> <span> 設定する</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control caution_content" name="caution_content" maxlength="5000" rows="10" placeholder="※最大5000文字まで">{!! old('caution_content',$plans->caution_content) !!}</textarea>
                                <span id="plan_caution_count" class="d-block text-lg-right">5000/5000</span>

                            </div>
                        </div>
<!--                         <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> その他の項目設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">持ち物</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="item" rows="4" placeholder="※最大1000文字まで">{{ old('item',$plans->item) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">服装</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="wear" rows="4" placeholder="※最大1000文字まで">{{ old('wear',$plans->wear) }}</textarea>
                            </div>
                        </div> -->
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> キャンセル規定</span></label>
                        </div>





                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> キャンセル規定</label>
                            <div class="col-md-8">
                                <textarea id="cancel" type="textarea" class="form-control" name="cancel" rows="4" placeholder="※最大1000文字まで">{{ old('cancel',$plans->cancel) }}</textarea>
                            </div>
                        </div>


                            
                        <div class="form-group row mt-5 bg-dark">
                            <label for="" class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 写真設定</span></label>
                        </div>
                        <p class="text-danger pl-4">※推奨サイズは横1200px、縦800pxです。画像アップロード容量は一枚1MBとなります</p>
                        <div class="form-group row">
                            <br>
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('メイン画像') }}</label>
                            <div id="div-img1" class="col-md-4">
                              @empty ($plans->file_path1)
                              <input type="file" name="file_path1">
                              @else
                              <img id="img1" src="{{config('app.url')}}uploads/{{ $plans->file_path1 }}" width="auto" height="150px">
                              <input id="hidden1" type="hidden" name="old_file_path1" value="{{ old('file_path1',$plans->file_path1) }}">
                              @endempty
                            </div>
                            <div id="div-button1" class="col-md-4 file_path1">
                              @empty ($plans->file_path1)
                              <input type='button' id="clearbtn1" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(1)'/>
                              @else
                              <input type='button' id="deletebtn1" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(1)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像①') }}</label>
                            <div id="div-img2" class="col-md-4">
                              @empty ($plans->file_path2)
                              <input type="file" name="file_path2">
                              @else
                              <img id="img2" src="{{config('app.url')}}uploads/{{ $plans->file_path2 }}" width="auto" height="150px">
                              <input id="hidden2" type="hidden" name="old_file_path2" value="{{ old('file_path2',$plans->file_path2) }}">
                              @endempty
                            </div>
                            <div id="div-button2" class="col-md-4 file_path2">
                              @empty ($plans->file_path2)
                              <input type='button' id="clearbtn2" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(2)'/>
                              @else
                              <input type='button' id="deletebtn2" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(2)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像②') }}</label>
                            <div id="div-img3" class="col-md-4">
                              @empty ($plans->file_path3)
                              <input type="file" name="file_path3">
                              @else
                              <img id="img3" src="{{config('app.url')}}uploads/{{ $plans->file_path3 }}" width="auto" height="150px">
                              <input id="hidden3" type="hidden" name="old_file_path3" value="{{ old('file_path3',$plans->file_path3) }}">
                              @endempty
                            </div>
                            <div id="div-button3" class="col-md-4 file_path3">
                              @empty ($plans->file_path3)
                              <input type='button' id="clearbtn3" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(3)'/>
                              @else
                              <input type='button' id="deletebtn3" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(3)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像③') }}</label>
                            <div id="div-img4" class="col-md-4">
                              @empty ($plans->file_path4)
                              <input type="file" name="file_path4">
                              @else
                              <img id="img4" src="{{config('app.url')}}uploads/{{ $plans->file_path4 }}" width="auto" height="150px">
                              <input id="hidden4" type="hidden" name="old_file_path4" value="{{ old('file_path4',$plans->file_path4) }}">
                              @endempty
                            </div>
                            <div id="div-button4" class="col-md-4 file_path4">
                              @empty ($plans->file_path4)
                              <input type='button' id="clearbtn4" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(4)'/>
                              @else
                              <input type='button' id="deletebtn4" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(4)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像④') }}</label>
                            <div id="div-img5" class="col-md-4">
                              @empty ($plans->file_path5)
                              <input type="file" name="file_path5">
                              @else
                              <img id="img5" src="{{config('app.url')}}uploads/{{ $plans->file_path5 }}" width="auto" height="150px">
                              <input id="hidden5" type="hidden" name="old_file_path5" value="{{ old('file_path5',$plans->file_path5) }}">
                              @endempty
                            </div>
                            <div id="div-button5" class="col-md-4 file_path5">
                              @empty ($plans->file_path5)
                              <input type='button' id="clearbtn5" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(5)'/>
                              @else
                              <input type='button' id="deletebtn5" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(5)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑤') }}</label>
                            <div id="div-img6" class="col-md-4">
                              @empty ($plans->file_path6)
                              <input type="file" name="file_path6">
                              @else
                              <img id="img6" src="{{config('app.url')}}uploads/{{ $plans->file_path6 }}" width="auto" height="150px">
                              <input id="hidden6" type="hidden" name="old_file_path6" value="{{ old('file_path6',$plans->file_path6) }}">
                              @endempty
                            </div>
                            <div id="div-button6" class="col-md-4 file_path6">
                              @empty ($plans->file_path6)
                              <input type='button' id="clearbtn6" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(6)'/>
                              @else
                              <input type='button' id="deletebtn6" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(6)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑥') }}</label>
                            <div id="div-img7" class="col-md-4">
                              @empty ($plans->file_path7)
                              <input type="file" name="file_path7">
                              @else
                              <img id="img7" src="{{config('app.url')}}uploads/{{ $plans->file_path7 }}" width="auto" height="150px">
                              <input id="hidden7" type="hidden" name="old_file_path7" value="{{ old('file_path7',$plans->file_path7) }}">
                              @endempty
                            </div>
                            <div id="div-button7" class="col-md-4 file_path7">
                              @empty ($plans->file_path7)
                              <input type='button' id="clearbtn7" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(7)'/>
                              @else
                              <input type='button' id="deletebtn7" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(7)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑦') }}</label>
                            <div id="div-img8" class="col-md-4">
                              @empty ($plans->file_path8)
                              <input type="file" name="file_path8">
                              @else
                              <img id="img8" src="{{config('app.url')}}uploads/{{ $plans->file_path8 }}" width="auto" height="150px">
                              <input id="hidden8" type="hidden" name="old_file_path8" value="{{ old('file_path8',$plans->file_path8) }}">
                              @endempty
                            </div>
                            <div id="div-button8" class="col-md-4 file_path8">
                              @empty ($plans->file_path8)
                              <input type='button' id="clearbtn8" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(8)'/>
                              @else
                              <input type='button' id="deletebtn8" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(8)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑧') }}</label>
                            <div id="div-img9" class="col-md-4">
                              @empty ($plans->file_path9)
                              <input type="file" name="file_path9">
                              @else
                              <img id="img9" src="{{config('app.url')}}uploads/{{ $plans->file_path9 }}" width="auto" height="150px">
                              <input id="hidden9" type="hidden" name="old_file_path9" value="{{ old('file_path9',$plans->file_path9) }}">
                              @endempty
                            </div>
                            <div id="div-button9" class="col-md-4 file_path9">
                              @empty ($plans->file_path9)
                              <input type='button' id="clearbtn9" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(9)'/>
                              @else
                              <input type='button' id="deletebtn9" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(9)'/>
                              @endempty
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑨') }}</label>
                            <div id="div-img10" class="col-md-4">
                              @empty ($plans->file_path10)
                              <input type="file" name="file_path10">
                              @else
                              <img id="img10" src="{{config('app.url')}}uploads/{{ $plans->file_path10 }}" width="auto" height="150px">
                              <input id="hidden10" type="hidden" name="old_file_path10" value="{{ old('file_path10',$plans->file_path10) }}">
                              @endempty
                            </div>
                            <div id="div-button10" class="col-md-4 file_path10">
                              @empty ($plans->file_path10)
                              <input type='button' id="clearbtn10" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(10)'/>
                              @else
                              <input type='button' id="deletebtn10" class="btn btn-danger btn-sm" value='画像を削除' onClick='deleteFile(10)'/>
                              @endempty
                            </div>
                        </div>


                        <hr />

                        <div class="form-group row mt-5 bg-dark">
                            <label for="" class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 写真キャプション</span></label>
                        </div>

                        <p class="text-danger pl-4">※キャプションは40文字以内でご入力してください。</p>
						
						@php
						
							$image_captions = ['メインキャプション','サブキャプション①','サブキャプション①','サブキャプション②','サブキャプション③','サブキャプション④','サブキャプション⑤','サブキャプション⑥','サブキャプション⑦','サブキャプション⑧','サブキャプション⑨'];
					
						@endphp
                        @if($plans->caption == null)
							@foreach($image_captions as $key => $image_caption)
							<div class="form-group row">
								<label class="col-md-2 col-form-label text-md-right ml-4">{{ $image_caption }}</label>
								<div id="div-img10" class="col-md-6">
								 <input type="text" name="caption[]" class="form-control" value="{{ old('caption.'.$key) }}" maxlength="40">
								</div>
							</div>
							@endforeach
                        @else


							@foreach($image_captions as $key => $image_caption)
							<div class="form-group row">
								<label class="col-md-2 col-form-label text-md-right ml-4">{{ $image_caption }}</label>
								<div id="div-img10" class="col-md-6">
								 <input type="text" name="caption[]" class="form-control" value="{{ old('caption.'.$key,json_decode($plans->caption,true)[$key]) }}" maxlength="40">
								</div>
							</div>
							@endforeach
                        
                        @endif
                       
                        
                        <hr />



                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
<!--
                                <button type="submit" class="btn btn-primary" name='action' value='edit'>
                                    {{ __('変更する') }}
                                </button>
-->
                                <input type="submit" class="btn btn-primary submit" data-action="{{config('app.url')}}client/plans/update/{{ $plans->id }}" value="変更する">

				<input type="submit" class="btn btn-warning submit" data-action="{{config('app.url')}}client/plans/update-tmp/{{ $plans->id }}" value="下書き保存">

                                <a href="{{config('app.url')}}client/plans" class="btn btn-secondary">戻る</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<!-- <link type="text/css" rel="stylesheet" href="{{config('app.url')}}{{ asset('css/colorbox.css')}}" > -->
<link type="text/css" rel="stylesheet" href="{{ asset('css/colorbox.css')}}" >
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
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
    pdf_val.name = 'file_path11';
    pdf_link.remove();
    document.querySelector(".delete_file").remove();
}



let textArea = document.querySelector(".catchphrase");
let planArea = document.querySelector(".plan_text");
let institutionArea = document.querySelector('.institution');
let transportationArea = document.querySelector('.transportation');
let commentArea = document.querySelector('.payment_comment');
let includeArea = document.querySelector('.included_item');
let questionArea = document.querySelectorAll('.question_content');
let cautionArea = document.querySelector('.caution_content');


let characterCounter = document.getElementById("char_count");
let plan_characterCounter = document.getElementById("plan_char_count");
let plan_institution_count = document.getElementById('plan_institution_count');
let plan_transportation_count = document.getElementById('plan_transportation_count');
let plan_comment_count = document.getElementById('plan_comment_count');
let plan_include_count = document.getElementById('plan_include_count');
let plan_question_count = document.querySelectorAll('.plan_question_count');
let plan_caution_count = document.getElementById('plan_caution_count');

const maxNumOfChars = 100;
const plan_maxNumOfChars = 1200;
const maxCommentOfChars = 200;
const maxOfChars = 5000;


const countCharacters = () => {
    let numOfEnteredChars = textArea.value.length;
    let counter = maxNumOfChars - numOfEnteredChars;
    characterCounter.textContent = counter + "/100";
    if (counter < 0) {
        characterCounter.style.color = "red";
    } else if (counter < 20) {
        characterCounter.style.color = "orange";
    } else {
        characterCounter.style.color = "black";
    }
};
const plancountCharacters = () => {
    let numOfEnteredChars = planArea.value.length;
    let counter = plan_maxNumOfChars - numOfEnteredChars;
    plan_characterCounter.textContent = counter + "/1200";
    if (counter < 0) {
        plan_characterCounter.style.color = "red";
    } else if (counter < 20) {
        plan_characterCounter.style.color = "orange";
    } else {
        plan_characterCounter.style.color = "black";
    }
};

// 利用宿泊施設
const planInstitutionCountCharacters = () => {
    let institution_numOfEnteredChars = institutionArea.value.length;
    let institution_counter = maxNumOfChars - institution_numOfEnteredChars;
    plan_institution_count.textContent = institution_counter + "/100";
    if (institution_counter < 0) {
        plan_institution_count.style.color = "red";
    } else if (institution_counter < 20) {
        plan_institution_count.style.color = "orange";
    } else {
        plan_institution_count.style.color = "black";
    }
};

    //利用交通機関

const planTransportationCountCharacters = () => {

    let transportation_numOfEnteredChars = transportationArea.value.length;
    let transportation_counter = maxNumOfChars - transportation_numOfEnteredChars;
    plan_transportation_count.textContent = transportation_counter + "/100";
    if (transportation_counter < 0) {
        characterCounter.style.color = "red";
    } else if (transportation_counter < 20) {
        plan_transportation_count.style.color = "orange";
    } else {
        plan_transportation_count.style.color = "black";
    }

};

//支払方法の補足・詳細

const planCommentCountCharacters = () => {

    let comment_numOfEnteredChars = commentArea.value.length;
    let comment_counter = maxCommentOfChars - comment_numOfEnteredChars;
    plan_comment_count.textContent = comment_counter + "/200";
    if (comment_counter < 0) {
        plan_comment_count.style.color = "red";
    } else if (comment_counter < 20) {
        plan_comment_count.style.color = "orange";
    } else {
        plan_comment_count.style.color = "black";
    }

};

//料金に含まれるもの

const planIncludeCountCharacters = () => {

    let include_numOfEnteredChars = includeArea.value.length;
    let include_counter = maxNumOfChars - include_numOfEnteredChars;
    plan_include_count.textContent = include_counter + "/100";
    if (include_counter < 0) {
        plan_include_count.style.color = "red";
    } else if (include_counter < 20) {
        plan_include_count.style.color = "orange";
    } else {
        plan_include_count.style.color = "black";
    }

};
//予約者への質問

//予約者への質問

const planQuestionCountCharacters = (element) => {

let question_numOfEnteredChars = element.value.length;
let question_counter = plan_maxNumOfChars - question_numOfEnteredChars;
console.log(element);
element.nextElementSibling.textContent = question_counter + "/1200";
if (question_counter < 0) {
    element.nextElementSibling.style.color = "red";
} else if (question_counter < 20) {
    element.nextElementSibling.style.color = "orange";
} else {
    element.nextElementSibling.style.color = "black";
}

};
//注意事項・その他

const planCautionCountCharacters = () => {

    let caution_numOfEnteredChars = cautionArea.value.length;
    let caution_counter = maxOfChars - caution_numOfEnteredChars;
    plan_caution_count.textContent = caution_counter + "/5000";
    if (caution_counter < 0) {
        plan_caution_count.style.color = "red";
    } else if (caution_counter < 20) {
        plan_caution_count.style.color = "orange";
    } else {
        plan_caution_count.style.color = "black";
    }

};

countCharacters();
plancountCharacters();
planInstitutionCountCharacters();
planTransportationCountCharacters();
planCommentCountCharacters();
planIncludeCountCharacters();
planCautionCountCharacters();
textArea.addEventListener("input", countCharacters);
planArea.addEventListener("input", plancountCharacters);
institutionArea.addEventListener("input", planInstitutionCountCharacters);
transportationArea.addEventListener("input", planTransportationCountCharacters);
commentArea.addEventListener("input", planCommentCountCharacters);
includeArea.addEventListener("input", planIncludeCountCharacters);
cautionArea.addEventListener("input", planCautionCountCharacters);
document.addEventListener('input', function(e) {
    e = e || window.event;
    if(e.target.classList.contains('question_content')){
        planQuestionCountCharacters(e.target);
    }
}, false);


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
        $('div[name="add-question"]').hide();
        $('textarea[name="question_content[]"]').prop('disabled', true);
        $('select[name="answer_flag"]').prop('disabled', true);
    } else {
        if(Number($('div[name="add-question"]').children('input').val()) < 6){
            $('div[name="add-question"]').show()
        }

        $('textarea[name="question_content[]"]').prop('disabled', false);
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
        // $('textarea[name="caution_content"]').prop('disabled', true);
        $('textarea[name="caution_content"]').summernote('disable');
    } else {
        // $('textarea[name="caution_content"]').prop('disabled', false);
        $('textarea[name="caution_content"]').summernote('enable');
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
    $(".after-price-section-" + (val)).load('{{config('app.url')}}/html/plan-price.php', function() {
        var priceTypes = @json($price_types);
        $.each(priceTypes,(i,val) => {
          $('select[name="price_type"]').append($('<option>').html(val.name).val(val.number));
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
        $('textarea[name="caution_content"]').summernote('disable');
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
    $('input[name="add-activity"]').val({{ count($plans->activities) }});
    $('input[name="add-price"]').val({{ count($plans->prices) }});

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
        url: "{{config('app.url')}}client/genres/json/" + name,
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

$('div[name="add-question"]').click(function(e) {
    e.stopPropagation();
    var val = Number($(this).children('input[type="hidden"]').val());
    if (val == 5) {
        $(this).hide();
    }
    $(this).children('input[name="add-question"]').val(val + 1);
    $(".after-question-section-" + (val)).load('{{config('app.url')}}html/plan-question.php', function() {
        $(".after-question-section-" + (val - 1)).find(".delete-plan").hide();   
        $(".delete-plan").click(function(){
            $(".after-question-section-" + (val)).remove();
            $(".after-question-section-" + (val - 1)).find(".delete-plan").show();  
            let valInput = Number($('input[name="add-question"]').val());
            if(valInput < 2){
               valInput = 2 ; 
            }
            $('input[name="add-question"]').val(valInput - 1);
            $('input[name="add-question"]').show();

            $(".after-question-section-" + (val + 1)).attr("class" , "after-question-section-" + (val))
        })
    });

    $(".after-question-section-" + val).after('<div class="after-question-section-' + (val + 1) + '"></div>');


})


$('div[name="add-road"]').click(function(e) {
    e.stopPropagation();
    var val = Number($(this).children('input[type="hidden"]').val());

    if (val == 5) {
        $(this).hide();
    }
    $(this).children('input[name="add-road"]').val(val + 1);
    console.log(val);
    $(".after-road-section-" + (val)).load('{{config('app.url')}}html/plan-road.php', function() {

        if ($('input[name="is_discount"]:checked').val() == 1) {
	    $('.discount-road').show();
        }
        $(".added-road-number-").attr('class', 'added-road-number-' + val);
        $(".added-road-number-" + val).text(val + 1);
        $("div.road-section-").attr('class', 'mt-5 road-section-' + (val + 1));
        $(".add-road-section-" + val).attr('name', 'add-road-section-' + val);
        $(".schedule").attr('class', 'schedule' + val);
        $(".schedule" + val).text($(".schedule" + val).text() +  (val + 1) + '日目');
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
$('#cancel').summernote({
    tabsize: 2,
    minHeight: 250
});
$('textarea[name="caution_content"]').summernote({
    tabsize: 2,
    minHeight: 250
});



var css_count = 0;

$("body").click(function(e) {
   
    if ( $(e.target).parents(".note-editor.note-frame.card").length ) {
        let cs = parseInt($(e.target).parents(".note-editor.note-frame.card").find(".note-popover.popover.in.note-image-popover.bottom").css("top")) - 100;
        if($(e.target).parents(".note-editor.note-frame.card").find(".note-popover.popover.in.note-image-popover.bottom").css("display") == 'block'){
            css_count++;
        }
        else{
            css_count = 0;
        }
        
        if(css_count < 2){
            $(e.target).parents(".note-editor.note-frame.card").find(".note-popover.popover.in.note-image-popover.bottom").css("top", cs + 'px')
        }
    } 
   
  });

</script>


<script src='https://maps.google.com/maps/api/js?key=AIzaSyCG9SfPt8adGSdlgWkq8jdbt64mYaPRkaM' type="text/javascript"></script>
<script src="{{ asset('/js/jquery.colorbox-min.js')}}"></script>
<script src="{{ asset('/js/map.js')}}"></script>
<script src="{{ asset('/js/map2.js')}}"></script>
<script src="{{ asset('/js/ajaxzip3.js')}}"></script>

@stop
