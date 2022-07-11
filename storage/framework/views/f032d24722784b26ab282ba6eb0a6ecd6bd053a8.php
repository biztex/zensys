<?php $__env->startSection('title', 'プラン設定'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="row mb-2">
  <div class="col-sm-6 mt-3">
    <p>プラン設定</p>
    <small>※表示順の数字をクリックすると順番を編集できます</small>
  </div>
  <div class="col-sm-6 mt-3">
    <a href="<?php echo e(config('app.url')); ?>client/plans/create" class="btn btn-primary float-right">新規追加</a>　
  </div>
</div>
<div id="result"></div>
<div class="row mb-2">
  <div class="col-sm-12 mt-4">
    <form method="post" name="form" action="<?php echo e(config('app.url')); ?>client/plans/destroy-selected">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="ids">
      <button type="submit" id="delete-selected" class="btn btn-secondary float-right" onClick="return confirmDeleteSelected()" >選択データを削除</button>　
    </form>
  </div>
</div>

<script>

new gridjs.Grid ({
  language: {
    'search': {
      'placeholder': '検索キーワード'
    },
    'pagination': {
      'previous': '前ページ',
      'next': '次ページ',
    },
    'loading': 'ロード中...',
    'noRecordsFound': 'データはありません',
    'error': 'データの取得に失敗しました',
  },
  pagination: {
    limit: 30
  },
  sort: true,
  search: true,
  style: {
    td: {
      padding: '5px 10px'
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
    {
      name: '表示順',
      formatter: (_, row) => gridjs.html(`
        <div class="row sort-ajax" style="cursor: pointer; margin-left: 45px;" data-id="${row.cells[1].data}" data-sort="${row.cells[2].data}">${row.cells[2].data}</div>
        <input class="input-sort-ajax" type="number" min="1" max="999" name="input-sort-ajax" style="display:none;" data-id="${row.cells[1].data}" value="${row.cells[2].data}" />
      `)
    },
    'プラン名','開始日','終了日','ステータス',
    { 
      name: 'データ操作',
      sort: false,
      formatter: (_, row) => gridjs.html(`
        <div class="row ml-2">
          <a href="<?php echo e(config('app.url')); ?>client/plans/edit/${row.cells[1].data}" class="btn btn-warning btn-sm">編集</a>　
          <a href="<?php echo e(config('app.url')); ?>client/plans/replicate/${row.cells[1].data}" class="btn btn-info btn-sm">複製</a>　
          <form method="post" name="form" action="<?php echo e(config('app.url')); ?>client/plans/destroy/${row.cells[1].data}">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-danger btn-sm" onClick="return confirmDelete()">削除</button>
          </form>
        </div>
      `)
    }
  ],
  server: {
    url: '<?php echo e(config('app.url')); ?>client/plans/json',
    then: data => data.map(data => 
      [ '', data.id, data.sort == null ? '' : data.sort, data.name.slice(0, 15), data.start_day, data.end_day, displayIsListed(data.is_listed)]
    )
  } 
}).render(document.getElementById('result'));
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/default.js')); ?>"></script>
<script>
function displayIsListed(val) {
  var name = '';
  switch (val){
  case 0:
    name = '休止';
    break;
  case 1:
    name = '掲載中';
    break;
  case 2:
    name = '下書き保存';
    break;
  }
  return name;
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/client/plans/index.blade.php ENDPATH**/ ?>