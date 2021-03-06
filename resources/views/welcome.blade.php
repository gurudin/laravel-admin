@extends(config('admin.extends_blade'))

@section('title')
Welcome
@endsection

@section('topbar')
  @parent
@endsection

@section('content')
<div class="col">
  <div class="jumbotron bg-white">
    <h1 class="display-4">Welcome, {{ Auth::user()->name }} !</h1>
    <p class="lead"><i class="fab fa-slideshare"></i> have successfully logged in laravel Admin.</p>
    <hr class="my-4">
    <p>You can encourage us here.</p>
    <a class="btn btn-light-primary btn-lg" href="https://github.com/gurudin/laravel-admin" target="_blank">
      <i class="fab fa-github-alt"></i> Github
    </a>
  </div>
</div>
@endsection
