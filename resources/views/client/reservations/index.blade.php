@extends('adminlte::page')

@section('title', '予約管理')

@section('content_header')
@stop

@section('content')
<script src="{{asset('js/gridjs.umd.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/mermaid.min.css') }}">
<div class="row mb-2">
  <div class="col-sm-6 mt-3">
    <p>予約管理</p>
  </div>
</div>
ステータス<select  name="status" onchange="chgSelect()" id="status">
  <option value=""></option>
  <option value="予約確定"  >予約確定</option>
  <option value="リクエスト予約" >リクエスト予約</option>
  <option value="未決済" >未決済</option>
  <option value="キャンセル" >キャンセル</option>
</select>
予約日<input type="date" name="date" id="date"  onchange="chgSelect()">
<div id="result"></div>
<div class="row mb-2">
  <div class="col-sm-12 mt-4">
    <form method="post" name="form" action="{{config('app.url')}}client/reservations/destroy-selected" id="form1">
      @csrf
      <select id="num" name="num" onchange="chgNum()">
        <option value=10>10
        <option value=20>20
        <option value=50>50
        <option value=100>100

      </select>件表示
      <input type="hidden" name="ids">
      <button type="submit" id="delete-selected" class="btn btn-secondary float-right" onClick="('#form1').prop('action',action='{{config('app.url')}}client/reservations/destroy-selected');return confirmDeleteSelected()" >選択データを削除</button><button type="button" id="csv-selected" class="btn btn-warning float-right" onClick="return confirmCsvelected()" >CSV DL</button>
    </form>
  </div>
</div>
<script>

function confirmCsvelected(){
    var ids = $('input[type="checkbox"]:checked').map(function(){
            return $(this).val();
        }).get()
    $('input[name="ids"]').val(ids); 

    $('#form1').prop('action',action='{{config('app.url')}}client/reservations/csv-selected')
    $("#form1").submit();

    return true;
    
}

function chgNum(){
  let num = $("#num").val();
  //$("#result").html("");
  mygrid.updateConfig({
    pagination: {
      limit:  num 
    },

  }).forceRender();
}

function chgSelect(){
  let status = $("#status").val();
  let date = $("#date").val();
  //$("#result").html("");
  mygrid.updateConfig({
    server: {
    url: '{{config('app.url')}}client/reservations/json?date='+date+'&status='+status,
    then: data => data.map(data => 
      ['', data.id, data.order_id, data.status, data.user.name_last + ' ' + data.user.name_first, data.plan.name.slice(0, 10),data.fixed_datetime.slice(0, 10) , data.created_at.slice(0, 10), displayPaymentMethod(data.payment_method)]
    )
  } 

  }).forceRender();
}

var num =10;
const mygrid = new gridjs.Grid ({

  language: {
    'search': {
      'placeholder': '検索キーワード'
    },

    'pagination': {
      'previous': '前のページ',
      'next': '次のページ',
    },
    'loading': 'ロード中...',
    'noRecordsFound': 'データはありません',
    'error': 'データの取得に失敗しました',
  },
  pagination: {
    limit: num
  },

  sort: true,
  search: true,
  style: {
    td: {
      padding: '7px 24px'
    }
  },
  columns: [
    { 
      id: 'id',
      name: gridjs.html('<span class="select-items">全選択/解除</span>'),
      width: '0px',
      sort: false,
      formatter: (_, row) => gridjs.html(`
        <div class="text-center">
          <input id="row-data" type="checkbox" name="row-data" value="${row.cells[1].data}">
        </div>
      `),
    },
    { 
      name: 'ID',
      sort: {
        enabled: true
      },
      width: '80px',
      formatter: (_, row) => gridjs.html(`
        <div class="text-center">
          ${row.cells[1].data}
        </div>
      `)
    },
    '予約番号','予約状況',' 名前','プラン名','出発日','予約受付日時', '決済方法',
    { 
      name: 'データ操作',
      sort: false,
        formatter: (_, row) => gridjs.html(`
          <div class="row">
            <a href="{{config('app.url')}}client/reservations/edit/${row.cells[1].data}" class="btn btn-warning btn-sm">編集</a>　
            <form method="post" name="form" action="{{config('app.url')}}client/reservations/destroy/${row.cells[1].data}">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm" onClick="return confirmDelete()">削除</button>
            </form>
          </div>
        `)
    }
  ],
  server: {
    url: '{{config('app.url')}}client/reservations/json',
    then: data => data.map(data => 
      ['', data.id, data.order_id, data.status, data.user.name_last + ' ' + data.user.name_first, data.plan.name.slice(0, 10),data.fixed_datetime.slice(0, 10), data.created_at.slice(0, 10), displayPaymentMethod(data.payment_method)]
    )
  } 
}).render(document.getElementById('result'));


</script>

@stop
@section('css')
<link rel="stylesheet" href="{{asset('css/style.css')}}">
@stop

@section('js')
<script type="text/javascript" src="{{asset('js/default.js')}}"></script>

<script>
function displayPaymentMethod(val) {
  var name = '';
  switch (val){
  case 0:
    name = '現地払い';
    break;
  case 1:
    name = '事前払い';
    break;
  case 2:
    name = 'コンビニ決済';
    break;
  case 3:
    name = 'クレジット決済';
    break;
  }
  return name;
}
</script>
@stop

