@extends(config('admin.extends_blade'))

@section('title')
Menu Save
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">Menus save</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.menu') }}" class="text-dark"><i class="fa fa-angle-left"></i> Back</a>
  </div>
</div>

<div class="col" id="app">
  <div class="col-body rounded-sm">
    <vue-form :model='init.m' :options='init.options' ref="form">
    </vue-form>
  </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('vendor/gurudin/js/vue-2.6.10.js') }}"></script>
<script src="{{ URL::asset('vendor/gurudin/js/vue-form.js') }}"></script> 
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      init: {
        m: @json($menu),
        options: {
          name: {label: 'Name', type: 'text', placeholder: 'Menu name', required: true, minlength: 2},
          parent: {type: 'slot', name: 'parent'},
          route: {type: 'slot', name: 'route'},
          order: {label: 'Order', type: 'number', placeholder: 'order', min: 0},
          data: {label: 'Data', type: 'textarea', placeholder: '{"icon": "fa fa-cubes"}'},
          btnSubmit: {label: '<i class="fa fa-save"></i> Save', type: 'submit', class: 'btn-light-success', func: this.save},
        },
      },
    };
  },
  created() {
    console.log(this.init.m);
    
  }
});
</script>
@endsection
