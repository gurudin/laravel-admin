@extends(config('admin.extends_blade'))

@section('title')
  @lang('admin::messages.menu.create-menu')
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang('admin::messages.menu.create-menu')</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.menu') }}" class="text-dark">
      <i class="fa fa-angle-left"></i> @lang('admin::messages.common.back')
    </a>
  </div>
</div>

<div class="col" id="app">
  <div class="col-body rounded-sm">
    <form>
      <div class="form-group">
        <label>@lang('admin::messages.menu.title')</label>
        <input type="text"
          class="form-control"
          :class="{'is-invalid':isValid && menu.title == ''}"
          v-model.trim="menu.title"
          placeholder="@lang('admin::messages.menu.title-text')">
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.menu.parent')</label>
        <input type="text" class="form-control" v-model.trim="menu.parent" placeholder="@lang('admin::messages.menu.parent')">

        {{-- Parent list --}}
        <div class="position-relative" v-if="parentBox">
          <div class="list-group position-absolute w-100">
            <button type="button" class="list-group-item list-group-item-action">
              Cras justo odio
            </button>
            <button type="button" class="list-group-item list-group-item-action">Dapibus ac facilisis in</button>
            <button type="button" class="list-group-item list-group-item-action">Morbi leo risus</button>
            <button type="button" class="list-group-item list-group-item-action">Porta ac consectetur ac</button>
          </div>
        </div>
        {{-- /Parent list --}}
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.menu.route')</label>
        <input type="text" class="form-control" v-model.trim="menu.route" placeholder="@lang('admin::messages.menu.route-text')">
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.menu.order')</label>
        <input type="number" class="form-control" v-model.number="menu.order" placeholder="@lang('admin::messages.menu.order')">
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.menu.data')</label>
        <textarea class="form-control" v-model.trim="menu.data" placeholder="@lang('admin::messages.menu.data')"></textarea>
      </div>

      <button type="button" class="btn btn-light-success" @click="save">
        @lang('admin::messages.common.save')
      </button>
    </form>
  </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('vendor/gurudin/js/vue-2.6.10.js') }}"></script>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      menu: @json($menu),
      isValid: false,
    };
  },
  computed: {
    parentBox() {
      return false;
    },
  },
  methods: {
    save(event) {
      this.isValid = true;
      if (this.menu.title == '') {
        return false;
      }

      if (!this.menu.id) {
        this.create(event);
      } else {
        this.update(event);
      }
    },
    create(event) {
      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading();
      axios.post('{{route("admin.post.menu.create")}}', this.menu).then(function(response) {
        return response.data;
      }).then(function(resp) {
        if (resp.status) {
          window.location = "{{ route('admin.menu') }}";
        } else {
          alert(resp.msg);
          $btn.loading("reset");
        }
      });
    },
    update(event) {
      
    }
  },
});
</script>
@endsection
