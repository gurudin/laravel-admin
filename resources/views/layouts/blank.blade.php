<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') {{ config('app.name', '') }}</title>
  
  @yield('css')

  <link href="{{ URL::asset('vendor/gurudin/css/custom.css') }}" rel="stylesheet">
</head>
<body>
  
  @yield('content')

  @yield('script')
</body>
</html>