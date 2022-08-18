@extends('adminlte::page')

@section('title', '予約編集')

@section('content_header')
@stop

@section('content')
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
                    <form action="{{config('app.url')}}client/reservations/update/{{ $reservations->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="id" class="col-md-2 col-form-label text-md-right">{{ __('ID') }}</label>
                            <div class="col-md-6">
                                <input id="id" class="input-group-text text-md-left" type="text" name="id" value="{{ old('$reservations->id', $reservations->id) }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('プラン名') }}</label>
                            <div class="col-md-6">
                                <a target="_blank" class="font-weight-bold" style="line-height: 2.4;" href="{{config('app.url')}}client/plans/edit/{{ $reservations->plan->id }}">{{ $reservations->plan->name }} <small>（別ページで開く）</small></i></a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約番号') }}</label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="{{ $reservations->order_id }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('受付タイプ') }}</label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="@if ($reservations->plan->res_type == '0') 即時 @elseif ($reservations->plan->res_type == '1') 即時・リクエスト併用 @else リクエスト予約 @endif" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約ステータス') }}</label>
                            <div class="col-md-3">
                                <select class="form-control" name="status">
                                  <option disabled selected>選択してください</option>
                                  <option value="予約確定" @if(old('status',$reservations->status)=='予約確定') selected  @endif>予約確定</option>
                                  <option value="リクエスト予約" @if(old('status',$reservations->status)=='リクエスト予約') selected  @endif>リクエスト予約</option>
                                  <option value="未決済" @if(old('status',$reservations->status)=='未決済') selected  @endif>未決済</option>
                                  <option value="キャンセル" @if(old('status',$reservations->status)=='キャンセル') selected  @endif>キャンセル</option>
                                  <option value="一部返金" @if(old('status',$reservations->status)=='一部返金') selected  @endif>一部返金</option>
                                </select>
                            </div>
                            <div class="col-md-4">
			        <small class="text-red">※クレジットカード決済の場合、決済日から60日を超えるとDGFT側の決済をキャンセルすることはできません</small>
                            </div>
                        </div>
                        @foreach ($reservations->plan->activities as $activity)
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">体験日時({{ $loop->index + 1 }})</label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="{{ $reservations->activity_date }}　 {{ $activity->start_hour }}:{{ $activity->start_minute }} 〜 {{ $activity->end_hour}}:{{ $activity->end_minute }}" disabled>
                            </div>
                        </div>
                        @endforeach
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約受付日時') }}</label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="{{ substr($reservations->created_at,0, 16) }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約確定日時') }}</label>
                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control" name="" value="{{ substr($reservations->fixed_datetime,0, 16) }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約者') }}</label>
                            <div class="col-md-6">
                                <a target="_blank" class="font-weight-bold" style="line-height: 2.4;" href="{{config('app.url')}}client/users/edit/{{ $reservations->user->id }}">{{ $reservations->user->name_last }} {{ $reservations->user->name_first }} <small>（別ページで開く）</small></i></a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約者への質問') }}</label>
                            <div class="col-md-6">
                                <textarea id="name" type="text" class="form-control" name="" rows="5" disabled>{{ $reservations->plan->question_content }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('予約者からの回答') }}</label>
                            <div class="col-md-6">
                                <textarea id="name" type="text" class="form-control" name="" rows="5" disabled>{{ $reservations->answer }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('支払方法') }}</label>
                            <div class="col-md-3">
			        <select name="payment_method" class="form-control">
				    <option value="" selected>選択してください</option>
				    <option value="0" @if(old('payment_method', $reservations->payment_method)=='0') selected @endif>現地払い</option>
				    <option value="1" @if(old('payment_method', $reservations->payment_method)=='1') selected @endif>事前払い</option>
				    <option value="2" @if(old('payment_method', $reservations->payment_method)=='2') selected @endif>コンビニ決済</option>
				    <option value="3" @if(old('payment_method', $reservations->payment_method)=='3') selected @endif>クレジットカード決済</option>
				</select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('その他 備考・特記事項') }}</label>
                            <div class="col-md-6">
                                <textarea id="name" type="text" class="form-control" name="memo" rows="5" placeholder="※登録された内容は、予約情報として保存されます。">{{ $reservations->memo }}</textarea>
                            </div>
                        </div>
                    <div class="form-group row mt-3 bg-dark">
                        <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-users"></i> 旅行参加者情報</span></label>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">参加者(代表者)氏名(漢字)</label>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('姓') }}</label>
                            <div class="col ml-2">
                                <input id="" type="text" class="form-control" name="add_name_last" value="{{ old('add_name_last',$reservations->add_name_last) }}" required>
                            </div>
                        </div>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('名') }}</label>
                            <div class="col ml-2">
                                <input id="" type="text" class="form-control" name="add_name_first"  value="{{ old('add_name_last',$reservations->add_name_first) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">参加者(代表者)氏名(カナ)</label>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('セイ') }}</label>
                            <div class="col ml-2">
                                <input id="" type="text" class="form-control" name="add_kana_last" value="{{ old('add_kana_last',$reservations->add_kana_last) }}" required>
                            </div>
                        </div>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('メイ') }}</label>
                            <div class="col ml-2">
                                <input id="" type="text" class="form-control" name="add_kana_first" value="{{ old('add_kana_first',$reservations->add_kana_first) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('年齢') }}</label>
                        <div class="col-md-3">
                            <input id="" type="text" class="form-control" name="add_age" value="{{ old('add_age',$reservations->add_age) }}" required >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('生年月日') }}</label>
                        <div class="col-md-3">
                            <input id="" type="date" class="form-control" name="add_birth" value="{{ old('add_birth',$reservations->add_birth) }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('郵便番号') }}</label>
                        <div class="col-md-3">
                            <input id="" type="text" class="form-control" name="add_postalcode" value="{{ old('add_postalcode',$reservations->add_postalcode) }}" onKeyUp="AjaxZip3.zip2addr(this,'','prefecture','address');" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> 都道府県</label>
                        <div class="col-md-3">
                            <select class="form-control" name="add_prefecture"> 
                                <option value="">選択してください</option>
                                 <option value="北海道" @if(old('add_prefecture',$reservations->add_prefecture)=='北海道') selected @endif>北海道</option>
                                <option value="青森県" @if(old('add_prefecture',$reservations->add_prefecture)=='青森県') selected @endif>青森県</option>
                                <option value="岩手県" @if(old('add_prefecture',$reservations->add_prefecture)=='岩手県') selected @endif>岩手県</option>
                                <option value="宮城県" @if(old('add_prefecture',$reservations->add_prefecture)=='宮城県') selected @endif>宮城県</option>
                                <option value="秋田県" @if(old('add_prefecture',$reservations->add_prefecture)=='秋田県') selected @endif>秋田県</option>
                                <option value="山形県" @if(old('add_prefecture',$reservations->add_prefecture)=='山形県') selected @endif>山形県</option>
                                <option value="福島県" @if(old('add_prefecture',$reservations->add_prefecture)=='福島県') selected @endif>福島県</option>
                                <option value="茨城県" @if(old('add_prefecture',$reservations->add_prefecture)=='茨城県') selected @endif>茨城県</option>
                                <option value="栃木県" @if(old('add_prefecture',$reservations->add_prefecture)=='栃木県') selected @endif>栃木県</option>
                                <option value="群馬県" @if(old('add_prefecture',$reservations->add_prefecture)=='群馬県') selected @endif>群馬県</option>
                                <option value="埼玉県" @if(old('add_prefecture',$reservations->add_prefecture)=='埼玉県') selected @endif>埼玉県</option>
                                <option value="千葉県" @if(old('add_prefecture',$reservations->add_prefecture)=='千葉県') selected @endif>千葉県</option>
                                <option value="東京都" @if(old('add_prefecture',$reservations->add_prefecture)=='東京都') selected @endif>東京都</option>
                                <option value="神奈川県" @if(old('add_prefecture',$reservations->add_prefecture)=='神奈川県') selected @endif>神奈川県</option>
                                <option value="新潟県" @if(old('add_prefecture',$reservations->add_prefecture)=='新潟県') selected @endif>新潟県</option>
                                <option value="富山県" @if(old('add_prefecture',$reservations->add_prefecture)=='富山県') selected @endif>富山県</option>
                                <option value="石川県" @if(old('add_prefecture',$reservations->add_prefecture)=='石川県') selected @endif>石川県</option>
                                <option value="福井県" @if(old('add_prefecture',$reservations->add_prefecture)=='福井県') selected @endif>福井県</option>
                                <option value="山梨県" @if(old('add_prefecture',$reservations->add_prefecture)=='山梨県') selected @endif>山梨県</option>
                                <option value="長野県" @if(old('add_prefecture',$reservations->add_prefecture)=='長野県') selected @endif>長野県</option>
                                <option value="岐阜県" @if(old('add_prefecture',$reservations->add_prefecture)=='岐阜県') selected @endif>岐阜県</option>
                                <option value="静岡県" @if(old('add_prefecture',$reservations->add_prefecture)=='静岡県') selected @endif>静岡県</option>
                                <option value="愛知県" @if(old('add_prefecture',$reservations->add_prefecture)=='愛知県') selected @endif>愛知県</option>
                                <option value="三重県" @if(old('add_prefecture',$reservations->add_prefecture)=='三重県') selected @endif>三重県</option>
                                <option value="滋賀県" @if(old('add_prefecture',$reservations->add_prefecture)=='滋賀県') selected @endif>滋賀県</option>
                                <option value="京都府" @if(old('add_prefecture',$reservations->add_prefecture)=='京都府') selected @endif>京都府</option>
                                <option value="大阪府" @if(old('add_prefecture',$reservations->add_prefecture)=='大阪府') selected @endif>大阪府</option>
                                <option value="兵庫県" @if(old('add_prefecture',$reservations->add_prefecture)=='兵庫県') selected @endif>兵庫県</option>
                                <option value="奈良県" @if(old('add_prefecture',$reservations->add_prefecture)=='奈良県') selected @endif>奈良県</option>
                                <option value="和歌山県" @if(old('add_prefecture',$reservations->add_prefecture)=='和歌山県') selected @endif>和歌山県</option>
                                <option value="鳥取県" @if(old('add_prefecture',$reservations->add_prefecture)=='鳥取県') selected @endif>鳥取県</option>
                                <option value="島根県" @if(old('add_prefecture',$reservations->add_prefecture)=='島根県') selected @endif>島根県</option>
                                <option value="岡山県" @if(old('add_prefecture',$reservations->add_prefecture)=='岡山県') selected @endif>岡山県</option>
                                <option value="広島県" @if(old('add_prefecture',$reservations->add_prefecture)=='広島県') selected @endif>広島県</option>
                                <option value="山口県" @if(old('add_prefecture',$reservations->add_prefecture)=='山口県') selected @endif>山口県</option>
                                <option value="徳島県" @if(old('add_prefecture',$reservations->add_prefecture)=='徳島県') selected @endif>徳島県</option>
                                <option value="香川県" @if(old('add_prefecture',$reservations->add_prefecture)=='香川県') selected @endif>香川県</option>
                                <option value="愛媛県" @if(old('add_prefecture',$reservations->add_prefecture)=='愛媛県') selected @endif>愛媛県</option>
                                <option value="高知県" @if(old('add_prefecture',$reservations->add_prefecture)=='高知県') selected @endif>高知県</option>
                                <option value="福岡県" @if(old('add_prefecture',$reservations->add_prefecture)=='福岡県') selected @endif>福岡県</option>
                                <option value="佐賀県" @if(old('add_prefecture',$reservations->add_prefecture)=='佐賀県') selected @endif>佐賀県</option>
                                <option value="長崎県" @if(old('add_prefecture',$reservations->add_prefecture)=='長崎県') selected @endif>長崎県</option>
                                <option value="熊本県" @if(old('add_prefecture',$reservations->add_prefecture)=='熊本県') selected @endif>熊本県</option>
                                <option value="大分県" @if(old('add_prefecture',$reservations->add_prefecture)=='大分県') selected @endif>大分県</option>
                                <option value="宮崎県" @if(old('add_prefecture',$reservations->add_prefecture)=='宮崎県') selected @endif>宮崎県</option>
                                <option value="鹿児島県" @if(old('add_prefecture',$reservations->add_prefecture)=='鹿児島県') selected @endif>鹿児島県</option>
                                <option value="沖縄県" @if(old('add_prefecture',$reservations->add_prefecture)=='沖縄県') selected @endif>沖縄県</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('住所') }}</label>
                        <div class="col-md-6">
                            <input id="" type="text" class="form-control" name="add_address" value="{{ old('add_address',$reservations->add_address) }}" required> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right"> {{ __('マンション・ビル名') }}</label>
                        <div class="col-md-6">
                            <input id="" type="text" class="form-control" name="add_building" value="{{ old('add_building',$reservations->add_building) }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('電話番号') }}</label>
                        <div class="col-md-6">
                            <input id="" type="text" class="form-control" name="add_telephone" value="{{ old('add_telephone',$reservations->add_telephone) }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">乗車地</label>
                        <div class="col-md-3">
                        <select name="add_boarding" id="" class="form-control">
                            @foreach($pieces as $piece)
                                <option value="{{$piece}}" @if(old('add_boarding',$reservations->add_boarding)== $piece) selected @endif>{{$piece}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">降車地</label>
                        <div class="col-md-3">
        
                            <select name="add_drop" id="" class="form-control">
                                @foreach($drops as $drop)
                                    <option value="{{$drop}}" @if(old('add_drop',$reservations->add_drop)== $drop) selected @endif>{{$drop}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @foreach( json_decode($reservations->companion_name_last) as $key=>$custom)
                    <div class="form-group row border-top pt-1">
                        <label for="" class="col-md-3 col-form-label text-md-right">同行者氏名(漢字)</label>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('姓') }}</label>
                            <div class="col ml-2">
                                <input id="" type="text" class="form-control" name="companion_name_last[]" value="{{ old('companion_name_last',$custom) }}" required> 
                            </div>
                        </div>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('名') }}</label>
                            <div class="col ml-2">
                                 <input id="" type="text" class="form-control" name="companion_name_first[]" value="{{ old('companion_name_first',json_decode($reservations->companion_name_first)[$key]) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">同行者氏名(カナ)</label>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('セイ') }}</label>
                            <div class="col ml-2">
                                <input id="" type="text" class="form-control" name="companion_kana_last[]" value="{{ old('companion_kana_last',json_decode($reservations->companion_kana_last)[$key]) }}" required >
                            </div>
                        </div>
                        <div class="row align-items-center col-md-3">
                            <label class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('メイ') }}</label>
                            <div class="col ml-2">
                            <input id="" type="text" class="for/m-control" name="companion_kana_first[]" value="{{ old('companion_kana_first',json_decode($reservations->companion_kana_first)[$key]) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('年齢') }}</label>
                        <div class="col-md-3">
                            <input id="" type="text" class="form-control" name="companion_age[]" value="{{ old('companion_age',json_decode($reservations->companion_age)[$key]) }}"  required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('生年月日') }}</label>
                        <div class="col-md-3">
                            <input id="" type="date" class="form-control" name="companion_birth[]" value="{{ old('companion_birth',date('Y-m-d', strtotime(json_decode($reservations->companion_birth)[$key]))) }}" required>
                        </div>


                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right"><span class="badge badge-danger">必須</span> {{ __('性別') }}</label>
                        <div class="col-md-3">
                            <select name="companion_gender[]" id="" class="form-control">
                                <option value="0"  @if(old('companion_gender',json_decode($reservations->companion_gender)[$key])=='0') selected @endif>男</option>
                                <option value="1" @if(old('companion_gender',json_decode($reservations->companion_gender)[$key])=='1') selected @endif>女</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">乗車地</label>
                        <div class="col-md-3">
                            <select name="companion_boarding[]" id="" class="form-control">
                                @foreach($pieces as $piece)
                                    <option value="{{$piece}}" @if(old('companion_boarding',json_decode($reservations->companion_boarding)[$key])== $piece) selected @endif>{{$piece}}</option>
                                @endforeach
                             </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label text-md-right">降車地</label>
                        <div class="col-md-3">
                             <select name="companion_drop[]" id="" class="form-control">
                                @foreach($drops as $drop)
                                    <option value="{{$drop}}" @if(old('companion_drop',json_decode($reservations->companion_drop)[$key])== $drop) selected @endif>{{$drop}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    @endforeach




                    <div class="form-group row mt-3 bg-dark">
                        <label class="col-md-10 col-form-label text-md-left"><span class="h5"><i class="fas fa-fw fa-th-list"></i> 料金情報</span></label>
                    </div>
                    <div class="form-group row mb-2 float-right">
                        <div class="col-md-2">
		    	<input type="button" class="custom-table btn btn-sm btn-primary" value="価格表カスタマイズ">
                        </div>
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
                    @php
                        $arr = ['大人　','子供　','幼児　'] ;
                        $tmp_arr=['a','b','c','d','e','f','g','h','i','j','k','l'];
                        $Sumpt = 0;
                        $Number_req = [];
                        $custom_view = true;
                        $linecount = 0;
                    @endphp
                    @if(array_key_exists('custom_flg', (array)$Number_of_reservations))
                        @if($Number_of_reservations->custom_flg == 1)
                            <?php $custom_view = false;$add_view = true; ?>
                            @if(array_key_exists('price_name', (array)$Number_of_reservations))
                                @for ($k=0; $k<6; $k++)
                                <tr>
                                    <td>
                                        <input type="text" id="price_name{{$k + 1}}" name="price_name{{$k + 1}}" value="{{ $Number_of_reservations->price_name->{$k+1} }}">
                                    </td>
                                        <td style="text-align: right; padding-left: 50px;"><input type="number" id="price{{$k + 1}}" name="typec_{{$k+1}}_price" value="{{ $Number_of_reservations->typec_price->{$k+1} }}" style="width: 80%;">円</td>
                                        <td style="text-align: right; padding-left: 50px;"><input type="number" class="col-md-6 text-right" id="per-number{{$k + 1}}" name="typec_{{$k+1}}_number" value="{{ $Number_of_reservations->typec_number->{$k+1} }}">名</td>
                                        <td id="per-text-price{{ ($k + 1) }}" style="text-align: right;">{{ number_format($Number_of_reservations->typec_price->{$k+1} * $Number_of_reservations->typec_number->{$k+1}) }}円</td>
                                        <input type="hidden" id="per-price{{ $k + 1 }}" value="{{ $Number_of_reservations->typec_price->{$k+1} * $Number_of_reservations->typec_number->{$k+1} }}"> 
                                        <?php $Sumpt += $Number_of_reservations->typec_price->{$k+1} * $Number_of_reservations->typec_number->{$k+1};$Number_req[$k+1]=$Number_of_reservations->typec_number->{$k+1};?>
                                        <td><input type="button" class="btn btn-default" value="-" onClick="delLine({{ $k + 1 }})"></td>
                                </tr>
                                @endfor
                                @for ($k=6; $k<20; $k++)
                                    @if(is_null($Number_of_reservations->price_name->{$k+1}))
                                        @if($add_view)
                                            <tr id="addline{{ $k }}"><td colspan="4" style="text-align: center;"><input type="button" class="btn btn-default" value="+" onClick="addLine({{ $k+1 }})"></td></tr>
                                            <?php $add_view = false; $linecount = $k; ?>
                                        @else
                                            <tr id="addline{{ $k }}" style="display: none;"><td colspan="4" style="text-align: center;"><input type="button" class="btn btn-default" value="+" onClick="addLine({{ $k+1 }})"></td></tr>
                                        @endif
                                <tr id="line{{ $k+1 }}" style="display: none;">
                                    @else
                                <tr id="line{{ $k+1 }}">
                                    @endif
                                    <td>
                                        <input type="text" id="price_name{{$k + 1}}" name="price_name{{$k + 1}}" value="{{ $Number_of_reservations->price_name->{$k+1} }}">
                                    </td>
                                        <td style="text-align: right; padding-left: 50px;"><input type="number" id="price{{$k + 1}}" name="typec_{{$k+1}}_price" value="{{ $Number_of_reservations->typec_price->{$k+1} }}" style="width: 80%;">円</td>
                                        <td style="text-align: right; padding-left: 50px;"><input type="number" class="col-md-6 text-right" id="per-number{{$k + 1}}" name="typec_{{$k+1}}_number" value="{{ $Number_of_reservations->typec_number->{$k+1} }}">名</td>
                                        <td id="per-text-price{{ ($k + 1) }}" style="text-align: right;">{{ number_format($Number_of_reservations->typec_price->{$k+1} * $Number_of_reservations->typec_number->{$k+1}) }}円</td>
                                        <input type="hidden" id="per-price{{ $k + 1 }}" value="{{ $Number_of_reservations->typec_price->{$k+1} * $Number_of_reservations->typec_number->{$k+1} }}"> 
                                        <?php $Sumpt += $Number_of_reservations->typec_price->{$k+1} * $Number_of_reservations->typec_number->{$k+1};$Number_req[$k+1]=$Number_of_reservations->typec_number->{$k+1};?>
                                        <td><input type="button" class="btn btn-default" value="-" onClick="delLine({{ $k + 1 }})"></td>
                                </tr>
                                @endfor
                                <input type="hidden" id="linecount" value="{{ $linecount }}">
                            @else
                            @for ($i=0; $i<count($arr); $i++)
                            <tr>
                                <td>
                                    <input type="text" id="price_name{{$i + 1}}" name="price_name{{$i + 1}}" value="{{ $arr[$i] . $priceName->name }}">
                                </td>
                                @for($j=0; $j<count($tmp_arr); $j++)
                                    @if(array_key_exists(sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],1), (array)$Number_of_reservations))
                                        @if(array_key_exists(sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1), (array)$Number_of_reservations))
                                            <td style="text-align: right; padding-left: 50px;"><input type="number" id="price{{$i + 1}}" name="typec_{{$i+1}}_price" value="{{ $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] }}" style="width: 80%;">円</td>
                                            <td style="text-align: right; padding-left: 50px;"><input type="number" class="col-md-6 text-right" id="per-number{{$i + 1}}" name="typec_{{$i+1}}_number" value="@php echo $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};@endphp">名</td>
                                            <td id="per-text-price{{ ($i + 1) }}" style="text-align: right;">{{ number_format($prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)}) }}円</td>
                                            <input type="hidden" id="per-price{{ $i + 1 }}" value="{{ $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)} }}">
                                            <?php $Sumpt += $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};$Number_req[$i+1]=$Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};?>
                                        @else
                                            <td style="text-align: right; padding-left: 50px;"><input type="number" id="price{{$i + 1}}" name="typec_{{$i+1}}_price" value="{{ $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] }}" style="width: 80%;">円</td>
                                            <td style="text-align: right; padding-left: 50px;"><input type="number" class="col-md-6 text-right" id="number{{$i + 1}}" name="typec_{{$i+1}}_number" value="0">名</td>
                                            <td id="per-text-price{{ ($i + 1) }}" style="text-align: right;">0円</td>
                                            <input type="hidden" id="per-price{{ $i + 1 }}" value="0">
                                        @endif
                                    @endif
                                @endfor
                            </tr>
                            @endfor
                            @for ($k=$i; $k<6; $k++)
                            <tr>
                                <td>
                                    <input type="text" id="price_name{{$k + 1}}" name="price_name{{$k + 1}}" value="">
                                </td>
                                    <td style="text-align: right; padding-left: 50px;"><input type="number" id="price{{$k + 1}}" name="typec_{{$k+1}}_price" value="0" style="width: 80%;">円</td>
                                    <td style="text-align: right; padding-left: 50px;"><input type="number" class="col-md-6 text-right" id="per-number{{$k + 1}}" name="typec_{{$k+1}}_number" value="0">名</td>
                                    <td id="per-text-price{{ ($k + 1) }}" style="text-align: right;">0円</td>
                                    <input type="hidden" id="per-price{{ $k + 1 }}" value="0"> 
                            </tr>
                            @endfor
                            @endif
                        @endif
                    @endif
                    @if($custom_view)
                        @for ($i=0; $i<count($arr); $i++)
                        <tr>
                            <td>
                                {{ $arr[$i] . $priceName->name }}
                            </td>
                            @for($j=0; $j<count($tmp_arr); $j++)
                                @if(array_key_exists(sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],1), (array)$Number_of_reservations))
                                    @if(array_key_exists(sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1), (array)$Number_of_reservations))
                                        <td style="text-align: right;">{{ number_format($prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))]) }} 円</td>
                                        <input type="hidden" id="price{{$i + 1}}" value="{{ $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] }}">
                                        <td style="text-align: right; padding-left: 50px;"><div class="row justify-content-center"><input id="per-number{{ ($i + 1) }}" class="number-input col-md-5" name="{{ sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1) }}" value="@php echo $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};@endphp"> <span style="line-height: 1.8;" class="col-md-2"> 名</span></div></td>
                                        <input type="hidden" class="col-md-6 text-right" id="number{{$i + 1}}" value="@php echo $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};@endphp">
                                        <td id="per-text-price{{ ($i + 1) }}" style="text-align: right;">{{ number_format($prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)}) }}円</td>
                                        <input type="hidden" id="per-price{{ $i + 1 }}" value="{{ $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)} }}">
                                        <?php $Sumpt += $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] * $Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};$Number_req[$i+1]=$Number_of_reservations->{sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1)};?>
                                    @else
                                        <td style="text-align: right;">{{ number_format($prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))]) }} 円</td>
                                        <input type="hidden" id="price{{$i + 1}}" value="{{ $prices[0][strtolower($tmp_arr[$j].'_'. ((int)$i + 1))] }}">
                                        <td style="text-align: right; padding-left: 50px;"><div class="row justify-content-center"><input id="per-number{{ ($i + 1) }}" class="number-input col-md-5" name="{{ sprintf('type%d_%s_%d_number', $typeid,$tmp_arr[$j],$i+1) }}" value="0"> <span style="line-height: 1.8;" class="col-md-2"> 名</span></div></td>
                                        <input type="hidden" class="col-md-6 text-right" id="number{{$i + 1}}" value="0">
                                        <td id="per-text-price{{ ($i + 1) }}" style="text-align: right;">0円</td>
                                        <input type="hidden" id="per-price{{ $i + 1 }}" value="0">
                                    @endif
                                @endif
                            @endfor

                        </tr>
                        @endfor
                    @endif
                    <tr>
                      <td colspan="4" class="bg-light font-weight-bold" style="text-align: center;">履歴メモ</td>
                    </tr>
                    <tr>
                      <td colspan="4" style="text-align: center;"><textarea width="100%" rows="5" class="col-md-6 text-left" id="memo2" name="memo2">{{ $reservations->memo2 }}</textarea></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="bg-light font-weight-bold">人数・金額合計</td>
                      <td id="total-number" style="text-align: center;" class="font-weight-bold"></td>
                      <td id="total-price" style="text-align: right;" class="font-weight-bold"></td>
                    </tr>
                    <?php $Possible_refund_amount = $Sumpt - $Sum_refund_amount; ?>
                      @if (count($Credit_Cancels) > 0)
                        <?php $cancnt = 0; ?>
                        @foreach ($Credit_Cancels as $Credit_Cancel)
                            @if ($cancnt == 0)
                            <tr>
                                @if ($Possible_refund_amount > 0)
                                    <td colspan="2" class="bg-light font-weight-bold" style="vertical-align: middle;">返金額(残￥{{ number_format($Possible_refund_amount) }})</td>
                                    @if ($Credit_Cancel->cancel_status == 'NG')
                                        <td id="credit-cancel-price_tables_name" colspan="2" style="text-align: right;" class="font-weight-bold"><input id="credit_cancel_price" style="text-align: right;width: 50%;" name="credit_cancel_price" value="{{ $Credit_Cancel->refund_amount }}">円
                                        <input type="button" class="btn btn-danger" value="返品" onClick="goCreditCancel1()"></td>
                                    @else
                                        <td id="credit-cancel-price_tables_name" colspan="2" style="text-align: right;" class="font-weight-bold"><input id="credit_cancel_price" style="text-align: right;width: 50%;" name="credit_cancel_price" value="0">円
                                        <input type="button" class="btn btn-danger" value="キャンセル" onClick="goCreditCancel1()"></td><td></td>
                                        </tr><tr>
                                        <td colspan="2" class="bg-light font-weight-bold" style="vertical-align: middle;"></td>
                                        <td id="credit_cancel_price{{ $cancnt }}" colspan="2" style="text-align: right;" class="font-weight-bold" name="credit_cancel_price{{ $cancnt }}" value={{ $Credit_Cancel->refund_amount }}>{{ $Credit_Cancel->refund_amount }} 円</td>
                                    @endif
                                @else
                                    <td colspan="2" class="bg-light font-weight-bold" style="vertical-align: middle;">返金額(残￥{{ number_format($Possible_refund_amount) }})</td>
                                    <td id="credit_cancel_price{{ $cancnt }}" colspan="2" style="text-align: right;" class="font-weight-bold" name="credit_cancel_price{{ $cancnt }}" value={{ $Credit_Cancel->refund_amount }}>{{ $Credit_Cancel->refund_amount }} 円</td>
                                @endif
                                <td>{{ $Credit_Cancel->cancel_status }}</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="2" class="bg-light font-weight-bold" style="vertical-align: middle;"></td>
                                <td id="credit_cancel_price{{ $cancnt }}" colspan="2" style="text-align: right;" class="font-weight-bold" name="credit_cancel_price{{ $cancnt }}" value={{ $Credit_Cancel->refund_amount }}>{{ $Credit_Cancel->refund_amount }} 円</td>
                                <td>{{ $Credit_Cancel->cancel_status }}</td>
                            </tr>
                            @endif
                            <?php $cancnt++; ?>
                        @endforeach
                      @else
                      <tr>
                        <td colspan="2" class="bg-light font-weight-bold" style="vertical-align: middle;">返金額(残￥{{ number_format($Possible_refund_amount) }})</td>
                        <td id="credit-cancel-price_tables_name" colspan="2" style="text-align: right;" class="font-weight-bold"><input id="credit_cancel_price" style="text-align: right;width: 50%;" name="credit_cancel_price" value="0">円
                        <input type="button" class="btn btn-danger" value="キャンセル" onClick="goCreditCancel1()"></td><td></td>
                      </tr>
                      @endif
                      <input type="hidden" id="credit_cancel_flg" name="credit_cancel_flg" value="0">
                  </tbody>
                </table>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
				<input type="submit" class="submit btn btn-primary" data-action="{{config('app.url')}}client/reservations/update/{{ $reservations->id }}" value="変更する">
				<input type="submit" class="submit-send btn btn-danger" data-action="{{config('app.url')}}client/reservations/sendpaymentmail/{{ $reservations->id }}" value="決済メール送信">
                <a href="{{config('app.url')}}client/reservations/previewpaymentmail/{{ $reservations->id }}" onclick="window.open('{{config('app.url')}}client/reservations/previewpaymentmail/{{ $reservations->id }}', '', 'width=950,height=500,scrollbars=yes');return false;" class="btn btn-primary">プレビュー</a>
                                <a href="{{config('app.url')}}client/reservations/" class="btn btn-secondary">戻る</a>
                            </div>
                            <input type="hidden" id="custom_flg" name="custom_flg" value="">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
<script type="text/javascript" src="{{asset('js/default.js')}}"></script>
<script>
$(document).ready(function(){
    var totalNumber = 0,
        totalPrice = 0;
@if(array_key_exists('custom_flg', (array)$Number_of_reservations) && array_key_exists('price_name', (array)$Number_of_reservations))
    @if($Number_of_reservations->custom_flg == 1)
        for (var i = 1 ; i <= 20 ; i++) {
    @endif
@else
    for (var i = 1 ; i <= 6 ; i++) {
@endif
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
@if(array_key_exists('custom_flg', (array)$Number_of_reservations) && array_key_exists('price_name', (array)$Number_of_reservations))
    @if($Number_of_reservations->custom_flg == 1)
        for (var i = 1 ; i <= 20 ; i++) {
    @endif
@else
    for (var i = 1 ; i <= 6 ; i++) {
@endif
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
$('.submit').click(function(event) {
    $(this).parents('form').attr('action', $(this).data('action'));
    $(this).click();
});
$('.submit-send').click(function() {
    $flag = true;
    @php
    if(array_key_exists('custom_flg', (array)$Number_of_reservations) && array_key_exists('price_name', (array)$Number_of_reservations)){
        if($Number_of_reservations->custom_flg == 1){
            for($i=1;$i<=20;$i++){
                if(array_key_exists($i, $Number_req)){
                    if(!is_null($Number_req[$i])){
                        echo "if($('#per-number" . $i . "').length){\nif($('#per-number" . $i . "').val() != " . $Number_req[$i] . "){\$flag = false;\n}\n}";
                    }
                }
            }
        }
    }else{
        for($i=1;$i<=6;$i++){
            if(array_key_exists($i, $Number_req)){
                echo "if($('#per-number" . $i . "').length){\nif($('#per-number" . $i . "').val() != " . $Number_req[$i] . "){\$flag = false;\n}\n}";
            }
        }
    }
    @endphp
    if($flag){
        var checked = confirm("【確認】送信してよろしいですか？");
        if (checked == true) {
            $(this).parents('form').attr('action', $(this).data('action'));
            $(this).parents('form').submit();
            return true;
        } else {
            return false;
        }
    }else{
        confirm("価格表を更新した後は「変更する」ボタンをクリック");
        return false;
    }

});
function goCreditCancel1() {
    //$(".popup-overlay, .popup-content").addClass("active");
    var checked = confirm("【確認】返金処理を実行してよろしいですか？");
    if (checked == true) {
        $('select[name="status"]').val("キャンセル");
        $('#credit_cancel_flg').val(1);
        $('.submit').parents('form').attr('action', '/client/reservations/update/{{ $reservations->id }}');
        $('.submit').parents('form').submit();
        return true;
    } else {
        return false;
    }
}
function goCreditCancel2() {
    $('select[name="status"]').val("キャンセル");
    $('#credit_cancel_flg').val(1);
    $('.submit').parents('form').attr('action', '/client/reservations/update/{{ $reservations->id }}');
    $('.submit').parents('form').submit();
      return true;
}
$(".close, .popup-overlay").on("click", function(){
  $(".popup-overlay, .popup-content").removeClass("active");
  return false;
});
$('.custom-table').click(function() {
    $('#custom_flg').val(1);
    $('.submit').parents('form').attr('action', '/client/reservations/update/{{ $reservations->id }}');
    $('.submit').parents('form').submit();
    return true;
});
function addLine($line) {
    $linecount = parseInt($('#linecount').val());
    $('#addline' + ($line - 1)).attr('style', 'display: none;');
    $('#addline' + $line).attr('style', '');
    $('#line' + $line).attr('style', '');
    if($linecount<20){
        $linecount = $linecount +1;
        $('#linecount').val($linecount);
    }
    return false;
}
function delLine($line) {
    $linecount = parseInt($('#linecount').val());
    for($i=$line;$i<=$linecount;$i++){
        $('#price_name' + $i).val($('#price_name' + ($i+1)).val());
        $('#price' + $i).val($('#price' + ($i+1)).val());
        $('#per-number' + $i).val($('#per-number' + ($i+1)).val());
    }
    if($linecount>6){
        $('#addline' + ($linecount - 1)).attr('style', '');
        $('#addline' + $linecount).attr('style', 'display: none;');
        $('#line' + ($linecount - 1)).attr('style', '');
        $('#line' + $linecount).attr('style', 'display: none;');
        $linecount = $linecount -1;
        $('#linecount').val($linecount);
    }
    return false;
}
</script>
@stop
