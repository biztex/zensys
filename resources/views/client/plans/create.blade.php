@extends('adminlte::page')

@section('title', 'プラン追加')

@section('content_header')
@stop

@section('content')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<p>
プラン追加
</p>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">項目を入力後、「追加する」ボタンを押してください</div>
                <div class="card-body">
                    {{--成功もしくは失敗のメッセージ--}}
                    @if (Session::has('message'))
                    <div class="alert alert-primary">
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
                    <form action="{{config('app.url')}}client/plans/store" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group row mt-3 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 基本設定</span></label>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('ツアーコード') }}</label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code" value="{{ old('code') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> サイトカテゴリ</label>
                            <div class="col-md-6">
                                <select class="form-control" name="kind">
                                     @foreach($kinds as $kind)
                                  <option value="{{$kind->number}}" @if(old('kind')==$kind->number) selected  @endif>{{$kind->name}}</option>

                                @endforeach
                                 
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('ステータス') }}</label>
                            <div class="col-md-6">
                                <select class="form-control" name="is_listed">
                                  <option disabled selected>選択してください</option>
                                  <option value="1" @if(old('is_listed')=='1') selected @endif>掲載中</option>
                                  <option value="0" @if(old('is_listed')=='0') selected @endif>休止</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('プラン名') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> キャッチコピー</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="catchphrase" rows="5" placeholder="※最大100文字まで">{{ old('catchphrase') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> プラン説明文</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="description" rows="15" placeholder="※最大1200文字まで">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 出発日</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="start_day" value="{{ old('start_day') }}">
                            </div>
                            <label class="col-md-1 col-form-label text-md-center">〜</label>
                            <div class="col-md-2">
                                <input id="name" type="date" class="form-control" name="end_day" value="{{ old('end_day') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">繰り返し設定</label>
                            <div class="col-md-2">
                                <select class="form-control" name="repetition_flag">
                                  <option value="0" @if(old('repetition_flag')=='0') selected @endif>曜日</option>
                                  <option value="1" @if(old('repetition_flag')=='1') selected @endif>日にち</option>
                                </select>
                            </div>
                            <p class="col-md-4 col-form-label text-md-left">※販売期間内で有効となります</p>
                        </div>
                        <div class="form-group row ml-5 week-flag">
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="monday" value="1" checked @if(old('monday')=='1') checked @endif> <span> 月</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="tuesday" value="1" checked @if(old('tuesday')=='1') checked @endif> <span> 火</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="wednesday" value="1" checked @if(old('wednesday')=='1') checked @endif> <span> 水</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="thursday" value="1" checked @if(old('thursday')=='1') checked @endif> <span> 木</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="friday" value="1" checked @if(old('friday')=='1') checked @endif> <span> 金</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="saturday" value="1" checked @if(old('saturday')=='1') checked @endif> <span> 土</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="sunday" value="1" checked @if(old('sunday')=='1') checked @endif> <span> 日</span>
                            </label>
                        </div>
                        <div class="form-group row ml-5 month-flag">
                            <label class="col-md-1 col-form-label text-md-right ml-5">毎月</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="repetition_day" value="{{ old('repetition_day') }}">
                            </div>
                            <label class="col-md-1 col-form-label text-md-right">日に設定</label>
                        </div>
                        <div class="form-group row week-flag">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">選択した曜日が<br />祝日の場合</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="holiday_selected" value="1" @if(old('holiday_selected', '1')=='1') checked @endif> <span> 開催する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="holiday_selected" value="0" @if(old('holiday_selected')=='0') checked @endif> <span> 開催しない</span>
                            </label>
                        </div>
                        <div class="form-group row week-flag">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">選択していない曜日が<br />祝日の場合</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="holiday_nonselected" value="1" @if(old('holiday_nonselected', '1')=='1') checked @endif> <span> 開催する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="holiday_nonselected" value="0" @if(old('holiday_nonselected')=='0') checked @endif> <span> 開催しない</span>
                            </label>
                        </div>
<!--                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">自動延長設定</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="is_autoextend" value="1" @if(old('holiday_selected')=='1') checked @endif> <span> 自動で延長する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="is_autoextend" value="0" @if(old('is_autoextend', '0')=='0') checked @endif> <span> 自動で延長しない</span>
                            </label>
                        </div> -->
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('目的地') }}</label>
                            <div class="col-md-6">
                                <input id="destination" type="text" class="form-control" name="destination" value="{{ old('destination') }}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('お食事') }}</label>
                            <div class="col-md-6">
                                <input id="eat" type="text" class="form-control" name="eat" value="{{ old('eat') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('旅行日程') }}</label>
                            <div class="col-md-6">
                                <input id="schedule" type="text" class="form-control" name="schedule" value="{{ old('schedule') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('最小催行人員') }}</label>
                            <div class="col-md-6">
                                <input id="min_cnt" type="text" class="form-control" name="min_cnt" value="{{ old('min_cnt') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('添乗員の有無') }}</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="conductor_selected" value="1" @if (old('conductor_selected')== 1) checked @endif> <span> あり</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="conductor_selected" value="0" @if (old('conductor_selected') == 0) checked @endif> <span> なし</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('受付締切日時') }}</label>
                            <div class="col-md-6">
                                <input id="deadline" type="text" class="form-control" name="deadline" value="{{ old('deadline') }}">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('旅行条件書') }}</label>
                            <div class="col-md-6">
                                <div class="col-md-12 p-0">
                                    <input id="notice" class="form-control" type="text" name="notice" value="{{ old('notice') }}">
                                </div>
                                <div class="col-md-12 mt-2">
                                    or<input type="file" class="col-md-6" name="file_path11" id="file_path11">
                                </div>
                               
                                


                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('利用宿泊施設') }}</label>
                            <div class="col-md-6">
                                <textarea id="institution" type="textarea" class="form-control" name="institution" rows="5" placeholder="※最大100文字まで">{{ old('institution') }}</textarea>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('利用交通機関') }}</label>
                            <div class="col-md-6">
                                <textarea id="transportation" type="textarea" class="form-control" name="transportation" rows="5" placeholder="※最大100文字まで">{{ old('transportation') }}</textarea>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('乗車地　設定') }}</label>
                            <div class="col-md-4">
                                <input id="boarding" type="text" class="form-control" name="boarding" value="{{ old('boarding') }}" placeholder="カンマ区切り複数">
                            </div>
                            <div class="col-md-2">
                                <select  class="form-control" name="boarding_type">
                                    <option value="1" @if (old('boarding_type') == 1) selected @endif>必須
                                    <option value="2" @if (old('boarding_type') == 2) selected @endif>必須ではない
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('降車地　設定') }}</label>
                            <div class="col-md-4">
                                <input id="drop" type="text" class="form-control" name="drop" value="{{ old('drop') }}" placeholder="カンマ区切り複数">
                            </div>
                            <div class="col-md-2">
                                <select  class="form-control" name="drop_type">
                                    <option value="1" @if (old('drop_type') == 1) selected @endif>必須
                                    <option value="2" @if (old('drop_type') == 2) selected @endif>必須ではない
                                </select>
                            </div>
                        </div>
                        <hr />


                        <hr />
  
                                                 <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 行程表</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 行程表 (1)</label>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('タイトル') }}</label>
                            <div class="col-md-6">
                                <input id="road_map_title" type="text" class="form-control" name="road_map_title1" value="{{ old('road_map_title1') }}">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('利用宿泊施設') }}</label>
                            <div class="col-md-6">
                                <input id="road_map_build" type="text" class="form-control" name="road_map_build1" value="{{ old('road_map_build1') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('朝食') }}</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat1_1" type="radio"  name="road_eat1_1" value="1" @if(old('road_eat1')) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                    <input id="road_eat1_0" type="radio"  name="road_eat1_1" value="0" @if(!old('road_eat1')) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label >
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('昼食') }}</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_1" type="radio"  name="road_eat2_1" value="1" @if(old('road_eat2')) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>

                                </label >
                                <label class="custom-control custom-radio pl-2  m-0 font-weight-normal" role="button">
                                    <input id="road_eat2_0" type="radio"  name="road_eat2_1" value="0" @if(!old('road_eat2')) checked @endif>
                                    <span class="custom-control-description">なし</span>
                                </label>
                            </div>
                            </div>
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> {{ __('夕食') }}</label>
                            <div class="col-md-6 d-flex align-items-center">
                                <label  class="custom-control custom-radio p-0 m-0 font-weight-normal" role="button">
                                <input id="road_eat3_1" type="radio"  name="road_eat3_1" value="1" @if(old('road_eat3')) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">あり</span>
                                </label >
                                <label  class="custom-control custom-radio pl-2 m-0 font-weight-normal" role="button">
                                    <input id="road_eat3_0" type="radio"  name="road_eat3_1" value="0" @if(!old('road_eat3')) checked @endif>
                                    <span class="custom-control-description" th:text="#{bool.true}">なし</span>
                                </label >
                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"> 行程表</label>
                            <div class="col-md-6">
                                <textarea id="road_map" class="form-control"  name="road_map1" rows="10" >{!! old('road_map1') !!}</textarea>
                            <!-- <textarea id="codex-editor" type="textarea" class="form-control" name="road_map1" id="road_map" rows="10" >{{ old('road_map1') }}</textarea>-->
                            </div> 
                        </div>
                        <div class="after-road-section-1"></div>
                                                <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name='add-road'>
                                    {{ __('+ 行程表を追加する') }}
                                    <input type="hidden" name="add-road" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label for="" class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 予約受付設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 予約受付タイプ</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="res_type" value="0" @if(old('res_type', '0')=='0') checked @endif> <span> 予約</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="res_type" value="2" @if(old('res_type')=='2') checked @endif> <span> リクエスト</span>
                            </label>
<!--                             <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="res_type" value="1" @if(old('res_type')=='1') checked @endif> <span> 併用</span>
                            </label> -->
                            
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 受付開始日時</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_before_day" value="{{ old('res_before_day') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_before_type">
                                  <option value="0" @if(old('res_before_type', '0')=='0') selected @endif>日前の</option>
                                  <option value="1" @if(old('res_before_type')=='1') selected @endif>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_before_time">
                                  <option value="00" @if(old('res_before_time', '00')=='00') selected @endif>00</option>
                                  <option value="01" @if(old('res_before_time')=='01') selected @endif>01</option>
                                  <option value="02" @if(old('res_before_time')=='02') selected @endif>02</option>
                                  <option value="03" @if(old('res_before_time')=='03') selected @endif>03</option>
                                  <option value="04" @if(old('res_before_time')=='04') selected @endif>04</option>
                                  <option value="05" @if(old('res_before_time')=='05') selected @endif>05</option>
                                  <option value="06" @if(old('res_before_time')=='06') selected @endif>06</option>
                                  <option value="07" @if(old('res_before_time')=='07') selected @endif>07</option>
                                  <option value="08" @if(old('res_before_time')=='08') selected @endif>08</option>
                                  <option value="09" @if(old('res_before_time')=='09') selected @endif>09</option>
                                  <option value="10" @if(old('res_before_time')=='10') selected @endif>10</option>
                                  <option value="11" @if(old('res_before_time')=='11') selected @endif>11</option>
                                  <option value="12" @if(old('res_before_time')=='12') selected @endif>12</option>
                                  <option value="13" @if(old('res_before_time')=='13') selected @endif>13</option>
                                  <option value="14" @if(old('res_before_time')=='14') selected @endif>14</option>
                                  <option value="15" @if(old('res_before_time')=='15') selected @endif>15</option>
                                  <option value="16" @if(old('res_before_time')=='16') selected @endif>16</option>
                                  <option value="17" @if(old('res_before_time')=='17') selected @endif>17</option>
                                  <option value="18" @if(old('res_before_time')=='18') selected @endif>18</option>
                                  <option value="19" @if(old('res_before_time')=='19') selected @endif>19</option>
                                  <option value="20" @if(old('res_before_time')=='20') selected @endif>20</option>
                                  <option value="21" @if(old('res_before_time')=='21') selected @endif>21</option>
                                  <option value="22" @if(old('res_before_time')=='22') selected @endif>22</option>
                                  <option value="23" @if(old('res_before_time')=='23') selected @endif>23</option>
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
                                <label class=col-form-label text-md-right" style="padding: 0 0 3px 0;">【予約】</label>
                            </div>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_end_day" value="{{ old('res_end_day') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_end_type">
                                  <option value="0" @if(old('res_end_type', '0')=='0') selected @endif>日前の</option>
                                  <option value="1" @if(old('res_end_type')=='1') selected @endif>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="res_end_time">
                                  <option value="00" @if(old('res_end_time', '00')=='00') selected @endif>00</option>
                                  <option value="01" @if(old('res_end_time')=='01') selected @endif>01</option>
                                  <option value="02" @if(old('res_end_time')=='02') selected @endif>02</option>
                                  <option value="03" @if(old('res_end_time')=='03') selected @endif>03</option>
                                  <option value="04" @if(old('res_end_time')=='04') selected @endif>04</option>
                                  <option value="05" @if(old('res_end_time')=='05') selected @endif>05</option>
                                  <option value="06" @if(old('res_end_time')=='06') selected @endif>06</option>
                                  <option value="07" @if(old('res_end_time')=='07') selected @endif>07</option>
                                  <option value="08" @if(old('res_end_time')=='08') selected @endif>08</option>
                                  <option value="09" @if(old('res_end_time')=='09') selected @endif>09</option>
                                  <option value="10" @if(old('res_end_time')=='10') selected @endif>10</option>
                                  <option value="11" @if(old('res_end_time')=='11') selected @endif>11</option>
                                  <option value="12" @if(old('res_end_time')=='12') selected @endif>12</option>
                                  <option value="13" @if(old('res_end_time')=='13') selected @endif>13</option>
                                  <option value="14" @if(old('res_end_time')=='14') selected @endif>14</option>
                                  <option value="15" @if(old('res_end_time')=='15') selected @endif>15</option>
                                  <option value="16" @if(old('res_end_time')=='16') selected @endif>16</option>
                                  <option value="17" @if(old('res_end_time')=='17') selected @endif>17</option>
                                  <option value="18" @if(old('res_end_time')=='18') selected @endif>18</option>
                                  <option value="19" @if(old('res_end_time')=='19') selected @endif>19</option>
                                  <option value="20" @if(old('res_end_time')=='20') selected @endif>20</option>
                                  <option value="21" @if(old('res_end_time')=='21') selected @endif>21</option>
                                  <option value="22" @if(old('res_end_time')=='22') selected @endif>22</option>
                                  <option value="23" @if(old('res_end_time')=='23') selected @endif>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時まで受付する</label>
                        </div>
                       
                        <div class="form-group row ml-5 req-section">

                            <div class="col-md-2">
                                <label class="col-form-label text-md-right" style="padding: 0 0 3px 0;">【リクエスト】</label>
                            </div>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="req_before_day" value="{{ old('req_before_day') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="req_before_type">
                                  <option value="0" @if(old('req_before_type', '0')=='0') selected @endif>日前の</option>
                                  <option value="1" @if(old('req_before_type')=='1') selected @endif>週間前の</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="req_before_time">
                                  <option value="00" @if(old('req_before_time', '00')=='00') selected @endif>00</option>
                                  <option value="01" @if(old('req_before_time')=='01') selected @endif>01</option>
                                  <option value="02" @if(old('req_before_time')=='02') selected @endif>02</option>
                                  <option value="03" @if(old('req_before_time')=='03') selected @endif>03</option>
                                  <option value="04" @if(old('req_before_time')=='04') selected @endif>04</option>
                                  <option value="05" @if(old('req_before_time')=='05') selected @endif>05</option>
                                  <option value="06" @if(old('req_before_time')=='06') selected @endif>06</option>
                                  <option value="07" @if(old('req_before_time')=='07') selected @endif>07</option>
                                  <option value="08" @if(old('req_before_time')=='08') selected @endif>08</option>
                                  <option value="09" @if(old('req_before_time')=='09') selected @endif>09</option>
                                  <option value="10" @if(old('req_before_time')=='10') selected @endif>10</option>
                                  <option value="11" @if(old('req_before_time')=='11') selected @endif>11</option>
                                  <option value="12" @if(old('req_before_time')=='12') selected @endif>12</option>
                                  <option value="13" @if(old('req_before_time')=='13') selected @endif>13</option>
                                  <option value="14" @if(old('req_before_time')=='14') selected @endif>14</option>
                                  <option value="15" @if(old('req_before_time')=='15') selected @endif>15</option>
                                  <option value="16" @if(old('req_before_time')=='16') selected @endif>16</option>
                                  <option value="17" @if(old('req_before_time')=='17') selected @endif>17</option>
                                  <option value="18" @if(old('req_before_time')=='18') selected @endif>18</option>
                                  <option value="19" @if(old('req_before_time')=='19') selected @endif>19</option>
                                  <option value="20" @if(old('req_before_time')=='20') selected @endif>20</option>
                                  <option value="21" @if(old('req_before_time')=='21') selected @endif>21</option>
                                  <option value="22" @if(old('req_before_time')=='22') selected @endif>22</option>
                                  <option value="23" @if(old('req_before_time')=='23') selected @endif>23</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">時まで受付する</label>
                        </div>
                        <hr />

                        <div class="form-group row res-limit-section">
                            <label class="col-md-2 col-form-label text-md-center" >在庫</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="res_limit_number" value="{{ old('res_limit_number') }}">
                            </div>
                            
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2 col-form-label text-md-right">1予約あたり最小人数	</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="min_number" value="{{ old('min_number') }}">
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">人</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">1予約あたり最大人数	</label>
                            <div class="col-md-1">
                                <input id="name" type="text" class="form-control" name="max_number" value="{{ old('max_number') }}">
                            </div>
                            <label class="col-md-3 col-form-label text-md-left">人</label>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 支払方法設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 支払方法</label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="card" value="1" @if (old('card') == 1) checked @endif> <span> クレジットカード決済</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="cvs" value="1" @if (old('cvs') == 1) checked @endif> <span> コンビニ決済</span>
                            </label>
    
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="prepay" value="1" @if (old('prepay') == 1) checked @endif> <span> 事前払い</span>
                            </label>

                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="spot" value="1" @if (old('spot') == 1) checked @endif> <span> 現地払い</span>
                            </label>

                            
                            
                        </div>
                        <div class="form-group row spot_payment_section">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 現地での支払方法</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="checkbox" name="cache_flag" value="0" @if(old('cache_flag', 0)=='1') checked @endif> <span> 現地現金払い</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="checkbox" name="card_flag" value="0" @if(old('card_flag', '0')=='1') checked @endif> <span> 現地カード払い</span>
                            </label>
                        </div>
                        <div class="form-group row card_section">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 現地での利用可能な<br />カード</label>
                            <label class="radio-inline">
                              <input class="ml-4" type="checkbox" name="visa" value="0"> <span> VISA</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="mastercard" value="0"> <span> Master Card</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="americanexpress" value="0"> <span> American Express</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="jcb" value="0"> <span> JCB</span>
                            </label>
                            <label class="radiobinline">
                              <input class="ml-4" type="checkbox" name="unionpay" value="0"> <span> 銀聯（ぎんれん）</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="diners" value="0"> <span> ダイナースクラブ</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-4" type="checkbox" name="discover" value="0"> <span> DISCOVER</span>
                            </label>
                        </div>
                        <div class="form-group row card_section">
                            <label class="checkbox-inline offset-md-2">
                              <input class="ml-4" type="checkbox" name="etc_card" value="0"> <span> その他（他に利用可能なカードがあれば右に入力）</span>
                            </label>
                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control" name="etc_card_name" value="{{ old('etc_card_name') }}" placeholder="※最大50文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 支払方法の補足・詳細</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="payment_comment" rows="2" placeholder="※最大200文字まで">{{ old('payment_comment') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 料金設定</span></label>
                        </div>
<!--                         <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> プロモーション設定</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="is_discount" value="0" @if(old('is_discount', '0')=='0') checked @endif> <span> 通常料金で販売する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="is_discount" value="1" @if(old('is_discount')=='1') checked @endif> <span> 割引料金で販売する</span>
                            </label>
                        </div>
                        <hr /> -->
                        <div class="mt-5 price-section-1">
                            <div class="form-group row">
                                <label class="col-md-2 mb-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 料金区分 (1)</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">【料金区分】</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="price_type1">
                                        @foreach ($price_types as $i => $ps)
                                        <option value="{{ $ps->number }}" @if(old('price_type1')==$ps->number) selected @endif>{{ $ps->name }}</option>
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
                                            <td><input type="number" name="price_a_1_1" value="{{ old('price_a_1_1') }}"></td>
                                            <td><input type="number" name="price_a_2_1" value="{{ old('price_a_2_1') }}"></td>
                                            <td><input type="number" name="price_a_3_1" value="{{ old('price_a_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>B</th>
                                            <td><input type="number" name="price_b_1_1" value="{{ old('price_b_1_1') }}"></td>
                                            <td><input type="number" name="price_b_2_1" value="{{ old('price_b_2_1') }}"></td>
                                            <td><input type="number" name="price_b_3_1" value="{{ old('price_b_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>C</th>
                                            <td><input type="number" name="price_c_1_1" value="{{ old('price_c_1_1') }}"></td>
                                            <td><input type="number" name="price_c_2_1" value="{{ old('price_c_2_1') }}"></td>
                                            <td><input type="number" name="price_c_3_1" value="{{ old('price_c_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>D</th>
                                            <td><input type="number" name="price_d_1_1" value="{{ old('price_d_1_1') }}"></td>
                                            <td><input type="number" name="price_d_2_1" value="{{ old('price_d_2_1') }}"></td>
                                            <td><input type="number" name="price_d_3_1" value="{{ old('price_d_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>E</th>
                                            <td><input type="number" name="price_e_1_1" value="{{ old('price_e_1_1') }}"></td>
                                            <td><input type="number" name="price_e_2_1" value="{{ old('price_e_2_1') }}"></td>
                                            <td><input type="number" name="price_e_3_1" value="{{ old('price_e_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>F</th>
                                            <td><input type="number" name="price_f_1_1" value="{{ old('price_f_1_1') }}"></td>
                                            <td><input type="number" name="price_f_2_1" value="{{ old('price_f_2_1') }}"></td>
                                            <td><input type="number" name="price_f_3_1" value="{{ old('price_f_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>G</th>
                                            <td><input type="number" name="price_g_1_1" value="{{ old('price_g_1_1') }}"></td>
                                            <td><input type="number" name="price_g_2_1" value="{{ old('price_g_2_1') }}"></td>
                                            <td><input type="number" name="price_g_3_1" value="{{ old('price_g_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>H</th>
                                            <td><input type="number" name="price_h_1_1" value="{{ old('price_h_1_1') }}"></td>
                                            <td><input type="number" name="price_h_2_1" value="{{ old('price_h_2_1') }}"></td>
                                            <td><input type="number" name="price_h_3_1" value="{{ old('price_h_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>I</th>
                                            <td><input type="number" name="price_i_1_1" value="{{ old('price_i_1_1') }}"></td>
                                            <td><input type="number" name="price_i_2_1" value="{{ old('price_i_2_1') }}"></td>
                                            <td><input type="number" name="price_i_3_1" value="{{ old('price_i_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>J</th>
                                            <td><input type="number" name="price_j_1_1" value="{{ old('price_j_1_1') }}"></td>
                                            <td><input type="number" name="price_j_2_1" value="{{ old('price_j_2_1') }}"></td>
                                            <td><input type="number" name="price_j_3_1" value="{{ old('price_j_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>K</th>
                                            <td><input type="number" name="price_k_1_1" value="{{ old('price_k_1_1') }}"></td>
                                            <td><input type="number" name="price_k_2_1" value="{{ old('price_k_2_1') }}"></td>
                                            <td><input type="number" name="price_k_3_1" value="{{ old('price_k_3_1') }}"></td>
                                        </tr>
                                        <tr>
                                            <th>L</th>
                                            <td><input type="number" name="price_l_1_1" value="{{ old('price_l_1_1') }}"></td>
                                            <td><input type="number" name="price_l_2_1" value="{{ old('price_l_2_1') }}"></td>
                                            <td><input type="number" name="price_l_3_1" value="{{ old('price_l_3_1') }}"></td>
                                        </tr>
                                    </table>
                             </div> 
                             
                             <div class="after-price-section-1"></div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <div type="" class="btn btn-default" name='add-price'>
                                    {{ __('+ 料金区分を追加する') }}
                                    <input type="hidden" name="add-price" value="1">
                                </div>
                            </div>
                        </div>
                        </div>

                          <div class="form-group row mt-5">
                            <label class="col-md-2 col-form-label text-md-right"> 料金に含まれるもの</label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="included_item" rows="2" placeholder="※最大100文字まで">{{ old('included_item') }}</textarea>
                            </div>
                        </div>

<!-- 

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right discount-price" style="padding: 0 0 3px 0;">【割引前の料金】</label>
                                <div class="col-md-3 discount-price">
                                    <input id="name" type="text" class="form-control" name="price_before_price1" value="{{ old('price_before_price1') }}">
                                </div>
                                <label class="col-md-1 col-form-label text-md-left discount-price">円</label>
                                <p class="col-md-6 col-form-label text-md-left discount-comment">※価格が取消し線付きで表示されます　 例）<strike>1,500円〜</strike> 1,000円～　</p>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">【販売料金】</label>
                                <div class="col-md-3">
                                    <input id="name" type="text" class="form-control" name="price_price1" value="{{ old('price_price1') }}">
                                </div>
                                <label class="col-md-1 col-form-label text-md-left">円</label>
                                <div class="col-md-3">
                                    <input class="" type="checkbox" name="price_week_flag1" value="0" @if(old('price_week_flag1', '0')=='1') checked @endif> <span> 曜日別に料金設定する</span>
                                </div>
                            </div>
                            <div class="form-group row ml-5 week-price-section-1">
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 月</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 火</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 水</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 木</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 金</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 土</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 日</label>
                                <label class="col-md-1 ml-4 col-form-label text-md-center"> 祝日</label>
                            </div>
                            <div class="form-group row ml-5 week-price-section-1">
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_monday1" value="{{ old('price_monday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_tuesday1" value="{{ old('price_tuesday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_wednesday1" value="{{ old('price_wednesday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_thursday1" value="{{ old('price_thursday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_friday1"  value="{{ old('price_friday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_saturday1" value="{{ old('price_saturday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_sunday1"  value="{{ old('price_sunday1') }}">
                                </div>
                                <div class="col-md-1 ml-4">
                                    <input id="name" type="text" class="form-control" name="price_holiday1" value="{{ old('price_holiday1') }}">
                                </div>
                            </div>
                        </div>
                        <div class="after-price-section-1"></div>
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
                                <textarea id="name" type="textarea" class="form-control" name="included_item" rows="2" placeholder="※最大100文字まで">{{ old('included_item') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 目的地設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 目的地名</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="place_name" value="{{ old('place_name') }}" placeholder="※最大120文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 郵便番号</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="place_postalcode" value="{{ old('place_postalcode') }}" onKeyUp="AjaxZip3.zip2addr(this,'','place_prefecture','place_address');" placeholder="自動で住所検索します（ハイフンありなし可）">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 都道府県</label>
                            <div class="col-md-3">
                                <select class="form-control" name="place_prefecture">
                                    <option value="" @if(old('place_prefecure', '')=='') selected @endif>選択してください</option>
                                    <option value="北海道" @if(old('place_prefecture')=='北海道') selected @endif>北海道</option>
                                    <option value="青森県" @if(old('place_prefecture')=='青森県') selected @endif>青森県</option>
                                    <option value="岩手県" @if(old('place_prefecture')=='岩手県') selected @endif>岩手県</option>
                                    <option value="宮城県" @if(old('place_prefecture')=='宮城県') selected @endif>宮城県</option>
                                    <option value="秋田県" @if(old('place_prefecture')=='秋田県') selected @endif>秋田県</option>
                                    <option value="山形県" @if(old('place_prefecture')=='山形県') selected @endif>山形県</option>
                                    <option value="福島県" @if(old('place_prefecture')=='福島県') selected @endif>福島県</option>
                                    <option value="茨城県" @if(old('place_prefecture')=='茨城県') selected @endif>茨城県</option>
                                    <option value="栃木県" @if(old('place_prefecture')=='栃木県') selected @endif>栃木県</option>
                                    <option value="群馬県" @if(old('place_prefecture')=='群馬県') selected @endif>群馬県</option>
                                    <option value="埼玉県" @if(old('place_prefecture')=='埼玉県') selected @endif>埼玉県</option>
                                    <option value="千葉県" @if(old('place_prefecture')=='千葉県') selected @endif>千葉県</option>
                                    <option value="東京都" @if(old('place_prefecture')=='東京都') selected @endif>東京都</option>
                                    <option value="神奈川県" @if(old('place_prefecture')=='神奈川県') selected @endif>神奈川県</option>
                                    <option value="新潟県" @if(old('place_prefecture')=='新潟県') selected @endif>新潟県</option>
                                    <option value="富山県" @if(old('place_prefecture')=='富山県') selected @endif>富山県</option>
                                    <option value="石川県" @if(old('place_prefecture')=='石川県') selected @endif>石川県</option>
                                    <option value="福井県" @if(old('place_prefecture')=='福井県') selected @endif>福井県</option>
                                    <option value="山梨県" @if(old('place_prefecture')=='山梨県') selected @endif>山梨県</option>
                                    <option value="長野県" @if(old('place_prefecture')=='長野県') selected @endif>長野県</option>
                                    <option value="岐阜県" @if(old('place_prefecture')=='岐阜県') selected @endif>岐阜県</option>
                                    <option value="静岡県" @if(old('place_prefecture')=='静岡県') selected @endif>静岡県</option>
                                    <option value="愛知県" @if(old('place_prefecture')=='愛知県') selected @endif>愛知県</option>
                                    <option value="三重県" @if(old('place_prefecture')=='三重県') selected @endif>三重県</option>
                                    <option value="滋賀県" @if(old('place_prefecture')=='滋賀県') selected @endif>滋賀県</option>
                                    <option value="京都府" @if(old('place_prefecture')=='京都府') selected @endif>京都府</option>
                                    <option value="大阪府" @if(old('place_prefecture')=='大阪府') selected @endif>大阪府</option>
                                    <option value="兵庫県" @if(old('place_prefecture')=='兵庫県') selected @endif>兵庫県</option>
                                    <option value="奈良県" @if(old('place_prefecture')=='奈良県') selected @endif>奈良県</option>
                                    <option value="和歌山県" @if(old('place_prefecture')=='和歌山県') selected @endif>和歌山県</option>
                                    <option value="鳥取県" @if(old('place_prefecture')=='鳥取県') selected @endif>鳥取県</option>
                                    <option value="島根県" @if(old('place_prefecture')=='島根県') selected @endif>島根県</option>
                                    <option value="岡山県" @if(old('place_prefecture')=='岡山県') selected @endif>岡山県</option>
                                    <option value="広島県" @if(old('place_prefecture')=='広島県') selected @endif>広島県</option>
                                    <option value="山口県" @if(old('place_prefecture')=='山口県') selected @endif>山口県</option>
                                    <option value="徳島県" @if(old('place_prefecture')=='徳島県') selected @endif>徳島県</option>
                                    <option value="香川県" @if(old('place_prefecture')=='香川県') selected @endif>香川県</option>
                                    <option value="愛媛県" @if(old('place_prefecture')=='愛媛県') selected @endif>愛媛県</option>
                                    <option value="高知県" @if(old('place_prefecture')=='高知県') selected @endif>高知県</option>
                                    <option value="福岡県" @if(old('place_prefecture')=='福岡県') selected @endif>福岡県</option>
                                    <option value="佐賀県" @if(old('place_prefecture')=='佐賀県') selected @endif>佐賀県</option>
                                    <option value="長崎県" @if(old('place_prefecture')=='長崎県') selected @endif>長崎県</option>
                                    <option value="熊本県" @if(old('place_prefecture')=='熊本県') selected @endif>熊本県</option>
                                    <option value="大分県" @if(old('place_prefecture')=='大分県') selected @endif>大分県</option>
                                    <option value="宮崎県" @if(old('place_prefecture')=='宮崎県') selected @endif>宮崎県</option>
                                    <option value="鹿児島県" @if(old('place_prefecture')=='鹿児島県') selected @endif>鹿児島県</option>
                                    <option value="沖縄県" @if(old('place_prefecture')=='沖縄県') selected @endif>沖縄県</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">住所</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="place_address" value="{{ old('place_address') }}" placeholder="※最大100文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">アクセス方法</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="place_access" value="{{ old('place_access') }}" placeholder="※最大120文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 地図設定</label>
                            <div style="display:none">
                                <div id="mapModal">
                                    <p id="map-title"><i class="fas fa-fw fa-map-pin"></i> 地図設定</p>
                                    <div id="map_canvas" style="width:100%; height:500px"></div>

                                    <div class="form-group row mt-4">
                                        <div class="col-md-4 offset-md-5">
                                            <div type="" class="btn btn-default" name='map-from-address'>
                                                {{ __('入力した住所から地図を表示') }}
                                                <input type="hidden" name="" value="1">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div type="" class="btn btn-primary" name='map-from-location'>
                                                {{ __('確定する') }}
                                                <input type="hidden" name="tmp_lat1">
                                                <input type="hidden" name="tmp_lng1">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div type="" href="#mapModal" id="map1" class="btn btn-default" name=''>
                                    <span>設定する</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-10 col-form-label text-md-left">緯度：<span class="lat1">{{ old('place_latitude') }}</span></label>
                                <input type="hidden" name="place_latitude" value="{{ old('place_latitude') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-10 col-form-label text-md-left">経度：<span class="lng1">{{ old('place_longitude') }}</span></label>
                                <input type="hidden" name="place_longitude" value="{{ old('place_longitude') }}">
                            </div>
                        </div>
                  
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;">集合場所</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="meeting_point_flag" value="0" @if(old('meeting_point_flag', '0')=='0') checked @endif> <span> 目的地と同じ場所を設定する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="meeting_point_flag" value="1" @if(old('meeting_point_flag')=='1') checked @endif> <span> 個別設定する</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="meeting_point_flag" value="2" @if(old('meeting_point_flag')=='2') checked @endif> <span> 設定しない</span>
                            </label>
                        </div> -->



                        <div class="meeting_point_section">
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 集合場所設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 集合場所名</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="meeting_point_name" value="{{ old('meeting_point_name') }}" placeholder="※最大120文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 郵便番号</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="meeting_point_postalcode" value="{{ old('meeting_point_postalcode') }}" onKeyUp="AjaxZip3.zip2addr(this,'','meeting_point_prefecture','meeting_point_address');" placeholder="自動で住所検索します（ハイフンありなし可）">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"> 都道府県</label>
                            <div class="col-md-3">
                                <select class="form-control" name="meeting_point_prefecture">
                                    <option value="" @if(old('meeting_point_prefecure', '')=='') selected @endif>選択してください</option>
                                    <option value="北海道" @if(old('meeting_point_prefecture')=='北海道') selected @endif>北海道</option>
                                    <option value="青森県" @if(old('meeting_point_prefecture')=='青森県') selected @endif>青森県</option>
                                    <option value="岩手県" @if(old('meeting_point_prefecture')=='岩手県') selected @endif>岩手県</option>
                                    <option value="宮城県" @if(old('meeting_point_prefecture')=='宮城県') selected @endif>宮城県</option>
                                    <option value="秋田県" @if(old('meeting_point_prefecture')=='秋田県') selected @endif>秋田県</option>
                                    <option value="山形県" @if(old('meeting_point_prefecture')=='山形県') selected @endif>山形県</option>
                                    <option value="福島県" @if(old('meeting_point_prefecture')=='福島県') selected @endif>福島県</option>
                                    <option value="茨城県" @if(old('meeting_point_prefecture')=='茨城県') selected @endif>茨城県</option>
                                    <option value="栃木県" @if(old('meeting_point_prefecture')=='栃木県') selected @endif>栃木県</option>
                                    <option value="群馬県" @if(old('meeting_point_prefecture')=='群馬県') selected @endif>群馬県</option>
                                    <option value="埼玉県" @if(old('meeting_point_prefecture')=='埼玉県') selected @endif>埼玉県</option>
                                    <option value="千葉県" @if(old('meeting_point_prefecture')=='千葉県') selected @endif>千葉県</option>
                                    <option value="東京都" @if(old('meeting_point_prefecture')=='東京都') selected @endif>東京都</option>
                                    <option value="神奈川県" @if(old('meeting_point_prefecture')=='神奈川県') selected @endif>神奈川県</option>
                                    <option value="新潟県" @if(old('meeting_point_prefecture')=='新潟県') selected @endif>新潟県</option>
                                    <option value="富山県" @if(old('meeting_point_prefecture')=='富山県') selected @endif>富山県</option>
                                    <option value="石川県" @if(old('meeting_point_prefecture')=='石川県') selected @endif>石川県</option>
                                    <option value="福井県" @if(old('meeting_point_prefecture')=='福井県') selected @endif>福井県</option>
                                    <option value="山梨県" @if(old('meeting_point_prefecture')=='山梨県') selected @endif>山梨県</option>
                                    <option value="長野県" @if(old('meeting_point_prefecture')=='長野県') selected @endif>長野県</option>
                                    <option value="岐阜県" @if(old('meeting_point_prefecture')=='岐阜県') selected @endif>岐阜県</option>
                                    <option value="静岡県" @if(old('meeting_point_prefecture')=='静岡県') selected @endif>静岡県</option>
                                    <option value="愛知県" @if(old('meeting_point_prefecture')=='愛知県') selected @endif>愛知県</option>
                                    <option value="三重県" @if(old('meeting_point_prefecture')=='三重県') selected @endif>三重県</option>
                                    <option value="滋賀県" @if(old('meeting_point_prefecture')=='滋賀県') selected @endif>滋賀県</option>
                                    <option value="京都府" @if(old('meeting_point_prefecture')=='京都府') selected @endif>京都府</option>
                                    <option value="大阪府" @if(old('meeting_point_prefecture')=='大阪府') selected @endif>大阪府</option>
                                    <option value="兵庫県" @if(old('meeting_point_prefecture')=='兵庫県') selected @endif>兵庫県</option>
                                    <option value="奈良県" @if(old('meeting_point_prefecture')=='奈良県') selected @endif>奈良県</option>
                                    <option value="和歌山県" @if(old('meeting_point_prefecture')=='和歌山県') selected @endif>和歌山県</option>
                                    <option value="鳥取県" @if(old('meeting_point_prefecture')=='鳥取県') selected @endif>鳥取県</option>
                                    <option value="島根県" @if(old('meeting_point_prefecture')=='島根県') selected @endif>島根県</option>
                                    <option value="岡山県" @if(old('meeting_point_prefecture')=='岡山県') selected @endif>岡山県</option>
                                    <option value="広島県" @if(old('meeting_point_prefecture')=='広島県') selected @endif>広島県</option>
                                    <option value="山口県" @if(old('meeting_point_prefecture')=='山口県') selected @endif>山口県</option>
                                    <option value="徳島県" @if(old('meeting_point_prefecture')=='徳島県') selected @endif>徳島県</option>
                                    <option value="香川県" @if(old('meeting_point_prefecture')=='香川県') selected @endif>香川県</option>
                                    <option value="愛媛県" @if(old('meeting_point_prefecture')=='愛媛県') selected @endif>愛媛県</option>
                                    <option value="高知県" @if(old('meeting_point_prefecture')=='高知県') selected @endif>高知県</option>
                                    <option value="福岡県" @if(old('meeting_point_prefecture')=='福岡県') selected @endif>福岡県</option>
                                    <option value="佐賀県" @if(old('meeting_point_prefecture')=='佐賀県') selected @endif>佐賀県</option>
                                    <option value="長崎県" @if(old('meeting_point_prefecture')=='長崎県') selected @endif>長崎県</option>
                                    <option value="熊本県" @if(old('meeting_point_prefecture')=='熊本県') selected @endif>熊本県</option>
                                    <option value="大分県" @if(old('meeting_point_prefecture')=='大分県') selected @endif>大分県</option>
                                    <option value="宮崎県" @if(old('meeting_point_prefecture')=='宮崎県') selected @endif>宮崎県</option>
                                    <option value="鹿児島県" @if(old('meeting_point_prefecture')=='鹿児島県') selected @endif>鹿児島県</option>
                                    <option value="沖縄県" @if(old('meeting_point_prefecture')=='沖縄県') selected @endif>沖縄県</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">住所</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="meeting_point_address" value="{{ old('meeting_point_address') }}" placeholder="※最大100文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">アクセス方法</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="meeting_point_access" value="{{ old('meeting_point_access') }}" placeholder="※最大120文字まで">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 地図設定</label>
                            <div style="display:none">
                                <div id="mapModal2">
                                    <p id="map-title"><i class="fas fa-fw fa-map-pin"></i> 地図設定</p>
                                    <div id="map_canvas2" style="width:100%; height:500px"></div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-4 offset-md-5">
                                            <div type="" class="btn btn-default" name='map-from-address2'>
                                                {{ __('入力した住所から地図を表示') }}
                                                <input type="hidden" name="" value="1">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div type="" class="btn btn-primary" name='map-from-location2'>
                                                {{ __('確定する') }}
                                                <input type="hidden" name="tmp_lat2">
                                                <input type="hidden" name="tmp_lng2">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div type="" href="#mapModal2" id="map2" class="btn btn-default" name=''>
                                    <span>設定する</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-10 col-form-label text-md-left">緯度：<span class="lat2">{{ old('meeting_point_latitude') }}</span></label>
                                <input type="hidden" name="meeting_point_latitude" value="{{ old('meeting_point_latitude') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-10 col-form-label text-md-left">経度：<span class="lng2">{{ old('meeting_point_longitude') }}</span></label>
                                <input type="hidden" name="meeting_point_longitude" value="{{ old('meeting_point_longitude') }}">
                            </div>
                        </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 予約者への質問・注意事項の設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 予約者への質問</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="question_flag" value="0" @if(old('question_flag', '0')=='0') checked @endif> <span> 設定しない</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="question_flag" value="1" @if(old('question_flag')=='1') checked @endif> <span> 設定する</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="question_content" rows="10" placeholder="※最大1200文字まで">{{ old('question_content') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 予約者からの回答</label>
                            <div class="col-md-6">
                                <select class="form-control" name="answer_flag">
                                  <option value="0" @if(old('answer_flag', '0')=='0') selected @endif>任意</option>
                                  <option value="1" @if(old('answer_flag')=='1') selected @endif>必須</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right" style="padding: 0 0 3px 0;"><span class="badge badge-danger">必須</span> 注意事項・その他</label>
                            <label class="radio-inline">
                              <input class="ml-5" type="radio" name="caution_flag" value="0" @if(old('caution_flag', '0')=='0') checked @endif> <span> 設定しない</span>
                            </label>
                            <label class="checkbox-inline">
                              <input class="ml-5" type="radio" name="caution_flag" value="1" @if(old('caution_flag')=='1') checked @endif> <span> 設定する</span>
                            </label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <textarea id="name" type="textarea" class="form-control" name="caution_content" rows="10" placeholder="※最大5000文字まで">{{ old('caution_content') }}</textarea>
                            </div>
                        </div>
<!--                         <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> その他の項目設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">持ち物</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="item" rows="4" placeholder="※最大1000文字まで">{{ old('item') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">服装</label>
                            <div class="col-md-8">
                                <textarea id="name" type="textarea" class="form-control" name="wear" rows="4" placeholder="※最大1000文字まで">{{ old('wear') }}</textarea>
                            </div>
                        </div> -->
                                                <div class="form-group row mt-5 bg-dark">
                            <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> キャンセル規定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> キャンセル規定</label>
                            <div class="col-md-8">
                                <textarea id="cancel" type="textarea" class="form-control" name="cancel" rows="4" placeholder="※最大1000文字まで">{!! old('cancel') !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mt-5 bg-dark">
                            <label for="" class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-cog"></i> 写真設定</span></label>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('メイン画像') }}</label>
                            <div id="div-img1" class="col-md-4">
                              <input type="file" name="file_path1" value="{{ old('file_path1') }}">
                            </div>
                            <div id="div-button1" class="col-md-4 file_path1">
                              <input type='button' id="clearbtn1" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(1)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像①') }}</label>
                            <div id="div-img2" class="col-md-4">
                              <input type="file" name="file_path2" value="{{ old('file_path2') }}">
                            </div>
                            <div id="div-button2" class="col-md-4 file_path2">
                              <input type='button' id="clearbtn2" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(2)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像②') }}</label>
                            <div id="div-img3" class="col-md-4">
                              <input type="file" name="file_path3" value="{{ old('file_path3') }}">
                            </div>
                            <div id="div-button3" class="col-md-4 file_path3">
                              <input type='button' id="clearbtn3" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(3)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像③') }}</label>
                            <div id="div-img4" class="col-md-4">
                              <input type="file" name="file_path4" value="{{ old('file_path4') }}">
                            </div>
                            <div id="div-button4" class="col-md-4 file_path4">
                              <input type='button' id="clearbtn4" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(4)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像④') }}</label>
                            <div id="div-img5" class="col-md-4">
                              <input type="file" name="file_path5" value="{{ old('file_path5') }}">
                            </div>
                            <div id="div-button5" class="col-md-4 file_path5">
                              <input type='button' id="clearbtn5" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(5)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑤') }}</label>
                            <div id="div-img6" class="col-md-4">
                              <input type="file" name="file_path6" value="{{ old('file_path6') }}">
                            </div>
                            <div id="div-button6" class="col-md-4 file_path6">
                              <input type='button' id="clearbtn6" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(6)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑥') }}</label>
                            <div id="div-img7" class="col-md-4">
                              <input type="file" name="file_path7" value="{{ old('file_path7') }}">
                            </div>
                            <div id="div-button7" class="col-md-4 file_path7">
                              <input type='button' id="clearbtn7" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(7)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑦') }}</label>
                            <div id="div-img8" class="col-md-4">
                              <input type="file" name="file_path8" value="{{ old('file_path8') }}">
                            </div>
                            <div id="div-button8" class="col-md-4 file_path8">
                              <input type='button' id="clearbtn8" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(8)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑧') }}</label>
                            <div id="div-img9" class="col-md-4">
                              <input type="file" name="file_path9" value="{{ old('file_path9') }}">
                            </div>
                            <div id="div-button9" class="col-md-4 file_path9">
                              <input type='button' id="clearbtn9" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(9)'/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-md-right">{{ __('サブ画像⑨') }}</label>
                            <div id="div-img10" class="col-md-4">
                              <input type="file" name="file_path10" value="{{ old('file_path10') }}">
                            </div>
                            <div id="div-button10" class="col-md-4 file_path10">
                              <input type='button' id="clearbtn10" class="btn btn-light btn-sm" value='画像選択を解除' onClick='clearFile(10)'/>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
<!--
                                <button type="submit" class="btn btn-primary" name='action' value='edit'>
                                    {{ __('追加する') }}
                                </button>
-->
                                <input type="submit" class="btn btn-primary submit" data-action="{{config('app.url')}}client/plans/store" value="追加する">
			
				<input type="submit" class="btn btn-warning submit" data-action="{{config('app.url')}}client/plans/store-tmp" value="下書き保存">
				
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
<link type="text/css" rel="stylesheet" href="{{ asset('/css/colorbox.css')}}" >
@stop

@section('js')
<script>

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
    $('select[name="genre_name"]').append('<option selected disabled>選択してください</option>');
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

    $(".after-price-section-" + (val)).load('{{config('app.url')}}html/plan-price.php', function() {
        var priceTypes = @json($price_types);
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

        $('input[name="price_tuesday"]').attr('name', 'price_tuesday' + (val + 1));
        $('input[name="price_wednesday"]').attr('name', 'price_wednesday' + (val + 1));
        $('input[name="price_thursday"]').attr('name', 'price_thursday' + (val + 1));
        $('input[name="price_friday"]').attr('name', 'price_friday' + (val + 1));
        $('input[name="price_saturday"]').attr('name', 'price_saturday' + (val + 1));
        $('input[name="price_sunday"]').attr('name', 'price_sunday' + (val + 1));
        $('input[name="price_holiday"]').attr('name', 'price_holiday' + (val + 1));
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


$('div[name="add-road"]').click(function(e) {
    e.stopPropagation();
    var val = Number($(this).children('input[type="hidden"]').val());
    if (val == 5) {
        $(this).hide();
    }
    $(this).children('input[name="add-road"]').val(val + 1);

    $(".after-road-section-" + (val)).load('{{config('app.url')}}html/plan-road.php', function() {

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

    // 送信ボタン切り分け
    $('.submit').click(function() {
        $(this).parents('form').attr('action', $(this).data('action'));
        $(this).parents('form').submit();
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



</script>
<script src='https://maps.google.com/maps/api/js?key=AIzaSyCG9SfPt8adGSdlgWkq8jdbt64mYaPRkaM' type="text/javascript"></script>
<script src="{{ asset('/js/jquery.colorbox-min.js')}}"></script>
<script src="{{ asset('/js/map.js')}}"></script>
<script src="{{ asset('/js/map2.js')}}"></script>
<script src="{{ asset('/js/ajaxzip3.js')}}"></script>
<!-- <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>

$('#road_map').summernote({
        tabsize: 2,
        minHeight: 250
      });
      $('#cancel').summernote({
        tabsize: 2,
        minHeight: 250
      });
// CKEDITOR.replace( 'road_map1' );
// CKEDITOR.replace( 'cancel' );
</script>

@stop

