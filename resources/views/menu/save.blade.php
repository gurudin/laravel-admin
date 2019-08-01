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
        <input type="text" class="form-control" v-model.trim="menu.parentName" @keyup="parentBox = true" placeholder="@lang('admin::messages.menu.parent')">

        {{-- Parent list --}}
        <div class="position-relative" v-if="parentBox">
          <div class="list-group position-absolute w-100" style="max-height: 500px; overflow: scroll;">
            <button type="button" class="list-group-item list-group-item-action" v-for="item in parentMenu" @click="selectParent(item)">
              <h5 class="list-group-item-heading">@{{item.title}}</h5>
              <span class="list-group-item-text text-muted">
                @{{item.parentName == null ? 'null' : item.parentName}} | @{{item.route == null ? 'null' : item.route}}
              </span>
            </button>
          </div>
        </div>
        {{-- /Parent list --}}
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.menu.route')</label>
        <input type="text" class="form-control" v-model.trim="menu.route" @keyup="routeBox = true" placeholder="@lang('admin::messages.menu.route-text')">

        {{-- Parent list --}}
        <div class="position-relative" v-if="routeBox">
          <div class="list-group position-absolute w-100" style="max-height: 500px; overflow: scroll;">
            <button type="button" class="list-group-item list-group-item-action" v-for="item in routesData" @click="selectRoute(item)">
              @{{item.name}}
            </button>
          </div>
        </div>
        {{-- /Parent list --}}
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
      menuList: @json($menu_list),
      routes: @json($routes),
      isValid: false,
      parentBox: false,
      routeBox: false,
    };
  },
  computed: {
    parentMenu() {
      var keyWord = this.menu.parentName == null ? '' : this.menu.parentName && this.menu.parentName.toLowerCase();

      var data = this.menuList.filter(row =>{
        return row.route == null && row.title.toLowerCase().indexOf(keyWord) > -1;
      });
      
      return keyWord == '' ? [] : data;
    },
    routesData() {
      var keyWord = this.menu.route == null ? '' : this.menu.route && this.menu.route.toLowerCase();

      var data = this.routes.filter(row =>{
        return row.name.toLowerCase().indexOf(keyWord) > -1;
      });

      return data;
    },
  },
  methods: {
    selectParent(item) {
      this.parentBox = false;

      this.menu.parentName = item.title;
      this.menu.parent = item.id;
    },
    selectRoute(item) {
      this.routeBox = false;

      this.menu.route = item.name;
    },
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
      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading();
      axios.put('{{route("admin.put.menu.update")}}', this.menu).then(function(response) {
        return response.data;
      }).then(function(resp) {
        if (resp.status) {
          window.location = "{{ route('admin.menu') }}";
        } else {
          alert(resp.msg);
          $btn.loading("reset");
        }
      });
    }
  },
});
</script>
@endsection
