@extends('admin::layouts.blank')

@section('title')
  @lang("admin::messages.login.login")
@endsection

@section('css')
  <link href="{{ URL::asset('vendor/gurudin/css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="account-pages mt-5 mb-5">
  <div class="container">
    <div class="justify-content-center row">
      <div class="col-lg-5">
        <div class="card">
          <div class="card-header pt-4 pb-4 text-center bg-light-primary text-white h4">
            @lang("admin::messages.login.login")
          </div>
          
          <div class="p-4 position-relative card-body">
            <div class="text-center w-75 m-auto">
              <p class="text-muted mb-4">@lang("admin::messages.login.enter-your-email-and-password-to-access-admin-panel")</p>
            </div>
            
            <form action="{{route('admin.post.login')}}" method="post">
              @csrf

              <input type="hidden" name="source" value="{{$source}}">

              <div class="form-group">
                <label>@lang("admin::messages.login.email")</label>
                <input type="text"
                  placeholder="@lang("admin::messages.login.enter-your-email")"
                  autofocus
                  class="form-control @error('email') is-invalid @enderror"
                  name="email">
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="password" class="">@lang("admin::messages.login.password")</label>
                <input type="password"
                  placeholder="@lang("admin::messages.login.enter-your-password")"
                  class="form-control @error('password') is-invalid @enderror"
                  name="password">
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="form-group">
                <button class="btn btn-light-success">@lang("admin::messages.login.login")</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-1 row">
      <div class="col-12 text-center col">
        <p class="text-muted">@lang("admin::messages.login.don't-have-an-account?")
          <a class="text-muted ml-1" href="{{route('admin.register')}}"><b>@lang("admin::messages.register.register")</b></a>
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
