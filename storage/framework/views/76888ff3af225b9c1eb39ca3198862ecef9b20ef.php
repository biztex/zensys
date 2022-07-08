<?php $__env->startSection('title', '管理者管理'); ?>

<?php $__env->startSection('content_header'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-2">
  <div class="col-sm-6 mt-3">
    <p>管理者管理</p>
  </div>
  <div class="col-sm-6 mt-3">
    <a href="<?php echo e(config('app.url')); ?>client/create" class="btn btn-primary float-right">新規追加</a>　
  </div>
</div>
<div id="result"></div>
<div class="row mb-2">
  <div class="col-sm-12 mt-4">
    <form method="post" name="form" action="<?php echo e(config('app.url')); ?>client/destroy-selected">
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
      'previous': '前のページ',
      'next': '次のページ',
    },
    'loading': 'ロード中...',
    'noRecordsFound': 'データはありません',
    'error': 'データの取得に失敗しました',
  },
  pagination: {
    limit: 10
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
    {
      name: 'メールアドレス',
      width: '300px',
    },
    {
      name: '名前',
      width: '360px',
    },
    '登録日',
    { 
      name: 'データ操作',
      sort: false,
        formatter: (_, row) => gridjs.html(`
          <div class="row">
            <a href="<?php echo e(config('app.url')); ?>client/edit/${row.cells[1].data}" class="btn btn-warning btn-sm">編集</a>　
            <form method="post" name="form" action="<?php echo e(config('app.url')); ?>client/destroy/${row.cells[1].data}">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-danger btn-sm" onClick="return confirmDelete()">削除</button>
            </form>
          </div>
        `)
    }
  ],
  server: {
    url: '<?php echo e(config('app.url')); ?>client/json',
    then: data => data.map(data => 
      ['', data.id, data.email, data.name, data.created_at.slice(0, 10)]
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/zenryo/resources/views/client/index.blade.php ENDPATH**/ ?>