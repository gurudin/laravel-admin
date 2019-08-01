@extends(config('admin.extends_blade'))

@section('title')
403 Forbidden
@endsection

@section('css')

@endsection

@section('content')
<div class="col-12" id="app">
  <div class="text-center text-muted mt-3 pt-3">
    <h1 class="font-weight-bold text-monospace">403 Forbidden</h1>
    <i class="fa fa-exclamation-triangle"></i>  You are not allowed to view this page in current region.
    <p>在当前区域，你没有权限查看此页面。</p>

    <div class="mt-3">
      <small>The above error occurred while processing request. please contact us with the screenshot of this page.</small><br>
      <small>处理请求时遇到系统错误，请再试一次。</small>
    </div>
  </div>
</div>
@endsection

