@extends('admin::layouts.blank')

@section('title')
Login
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
            Sign In
          </div>
          
          <div class="p-4 position-relative card-body">
            <div class="text-center w-75 m-auto">
              <p class="text-muted mb-4">Enter your email and password to access admin panel.</p>
            </div>
            
            <form action="{{route('admin.post.login')}}" method="post">
              @csrf

              <input type="hidden" name="source" value="{{$source}}">

              <div class="form-group">
                <label>Email</label>
                <input type="text" placeholder="Enter your email" autofocus class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="4008353@qq.com">
                @if ($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>
              
              <div class="form-group">
                <label for="password" class="">Password</label>
                <input type="password" placeholder="Enter your password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" value="gaoxiang">
                @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                @endif
              </div>
              
              <div class="form-group">
                <button class="btn btn-light-success">Submit</button>
              </div>
              <p>
                <strong>Email:</strong> 4008353@qq.com &nbsp;&nbsp;
                <strong>Password:</strong> gaoxiang
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-1 row">
      <div class="col-12 text-center col">
        <p class="text-muted">Don't have an account? 
          <a class="text-muted ml-1" href="{{route('admin.register')}}"><b>Register</b></a>
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
