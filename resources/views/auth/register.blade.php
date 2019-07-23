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
            Create Your Account
          </div>
          
          <div class="p-4 position-relative card-body">
            <div class="text-center w-75 m-auto">
              <p class="text-muted mb-4">Don't have an account? Create your account, it takes less than a minute</p>
            </div>
            
            <form action="{{route('admin.post.register')}}" method="post">
              @csrf

              <div class="form-group">
                <label>Full Name</label>
                <input type="text"
                  placeholder="Enter your name"
                  name="name"
                  class="form-control @error('name') is-invalid @enderror"
                  value="{{ old('name') }}"
                  autofocus>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="form-group">
                <label>Email address</label>
                <input type="text"
                  placeholder="Enter your email"
                  name="email"
                  value="{{ old('email') }}"
                  class="form-control @error('email') is-invalid @enderror">
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="form-group">
                <label>Password</label>
                <input type="password"
                  placeholder="Enter your password"
                  name="password"
                  class="form-control @error('password') is-invalid @enderror">
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="form-group">
                <label>Confirm Password</label>
                <input type="password"
                  placeholder="Confirm password"
                  name="c_password"
                  class="form-control @error('c_password') is-invalid @enderror">
                @error('c_password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="form-group">
                <button class="btn btn-light-success">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-1 row">
      <div class="col-12 text-center col">
        <p class="text-muted">Already have an account? 
          <a class="text-muted ml-1" href="{{route('admin.login')}}"><b>Sign In</b></a>
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
