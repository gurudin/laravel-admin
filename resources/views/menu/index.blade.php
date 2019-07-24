@extends(config('admin.extends_blade'))

@section('title')
Menu
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">Menus</h5>
  </div>
  <div class="float-right text-monospace">
    {{-- <a href="#" class="text-dark"><i class="fa fa-angle-left"></i> Back</a> --}}
    <a href="{{ route('admin.menu.save') }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> Create</a>
  </div>
</div>

<div class="col">
  <div class="col-body rounded-sm">
    11
  </div>
</div>
@endsection
